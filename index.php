<?php
/**
* Economic Calendar API.
* 
* It uses the investing.com as data source and using "web crawling" techniques.
* Revelant data is captured and returned in a JSON.
* 
* By default cache is used in order make the API faster.
* 
* @author André Lima <andrelimamail@gmail.com>
* @license MIT
* @version 1.0
*/

include_once(getcwd() . "/vendor/autoload.php");
use KubAT\PhpSimple\HtmlDomParser;

class EconomicCalendarAPI {
    private $cacheSuffix = "_api-cache.json";
    private $minGapFromNextEvent = 300; // If next event is timed for the next 5 minutes, cache won't be used
    private $dataSourceUrl = "https://sslecal2.forexprostools.com/";
    private $cacheFile = false; // File path to cache

    public function fetchEvents() {
        header("Content-Type: application/json");
        
        $this->setupCacheFile();

        if ($this->shouldUseCache()) {
            echo "TRUE:".$this->cacheFile."\n";
            $this->serveFromCache();
        } else {
            echo "false\n";
            $this->fetchAndCacheData();
        }
    }
    
    private function setupCacheFile() {
        $this->cacheFile = $this->findFirstCacheFile();
    }

    private function shouldUseCache() {
        if (!$this->cacheFile) return false;

        $cacheTime = $this->extractTimestampFromFilePath($this->cacheFile);
        echo "\nTIMEGAP:".($cacheTime - time())."\n";
        return ($cacheTime - time()) > $this->minGapFromNextEvent && (time() - $cacheTime) < $this->minGapFromNextEvent;
    }

    private function serveFromCache() {
        echo file_get_contents($this->cacheFile);
        exit();
    }

    private function fetchAndCacheData() {
        $dom = HtmlDomParser::file_get_html($this->dataSourceUrl);
        $data = $this->parseDataFromDom($dom);
        $this->cacheData($data);
        echo json_encode($data);
        exit();
    }

    private function findFirstCacheFile() {
        $files = glob("./*" . $this->cacheSuffix);
        return $files ? $files[0] : null;
    }

    private function extractTimestampFromFilePath($filePath) {
        if (preg_match('/(\d+)' . preg_quote($this->cacheSuffix) . '$/', $filePath, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }

    private function getMinuteDifference($dateString) {
        $specifiedDate = new DateTime($dateString);
        $serverDate = new DateTime("now");
        return ($specifiedDate->getTimestamp() - $serverDate->getTimestamp()) / 60;
    }

    private function sanitize($str) {
        return trim(str_replace("&nbsp;", "", $str));
    }

    private function parseDataFromDom($dom) {
        $elems = $dom->getElementById("#ecEventsTable")->find("tr[id*='eventRowId']");
        $events = [];

        $nextFutureEvent = null;
        $nextFutureEventSmallestDif = 9999;
                
        foreach($elems as $element) {
            $date = isset($element->attr["event_timestamp"]) ? $element->attr["event_timestamp"] : null;
            $name = count($name = $element->find("td.event")) > 0 ? $this->sanitize($name[0]->text()) : null;

            if($date != null) {
                $minutesDifference = $this->getMinuteDifference($date);
                if($minutesDifference > 0 && ($nextFutureEvent == null || $minutesDifference < $nextFutureEventSmallestDif)) {
                    $nextFutureEvent = $date;
                    $nextFutureEventSmallestDif = $minutesDifference;
                }
            }
            
            array_push($events, [
                "economy" => $this->sanitize($element->find("td.flagCur")[0]->text()),
                "impact" =>  count($element->find("td.sentiment")[0]->find("i.grayFullBullishIcon")),
                "date" => $date,
                "name" => $name,
                "actual" => count($actual = $element->find("td.act")) > 0 ? $this->sanitize($actual[0]->text()) : null,
                "forecast" => count($forecast = $element->find("td.fore")) > 0 ? $this->sanitize($forecast[0]->text()) : null,
                "previous" => count($previous = $element->find("td.prev")) > 0 ? $this->sanitize($previous[0]->text()) : null,
            ]);
        }

        return [
            "next_event_date"=> $nextFutureEvent,
            "events"=> $events,
        ];
    }

    private function cacheData($data) {
        if ($this->cacheFile) unlink($this->cacheFile);
        
        $nextEventDate = new DateTime($data['next_event_date']);
        $cacheFileName = $nextEventDate->getTimestamp() . $this->cacheSuffix;
        file_put_contents($cacheFileName, json_encode($data['events']));
    }
}

$api = new EconomicCalendarAPI();
$api->fetchEvents();
