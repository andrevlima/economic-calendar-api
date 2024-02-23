<?php
/**
* Economic Calendar API.
* 
* It uses the investing.com as data source and using "web crawling" techniques.
* Revelant data is captured and returned in a JSON.
* 
* There is no guarantees about the availability or estability of this API, changes 
* can be done in source page that can result in a crash of script.
*
* @author André Lima <andrelimamail@gmail.com>
* @license MIT
* @version 1.0
*/
header("Content-Type: application/json");

include_once(getcwd() . "/vendor/kub-at/php-simple-html-dom-parser/src/KubAT/PhpSimple/HtmlDomParser.php");
use KubAT\PhpSimple\HtmlDomParser;


function sanitize($str) {
	return trim(str_replace("&nbsp;", "", $str));
}

function findFirstCacheFile() {
    $suffix = "_api-cache.json";
    $files = glob("./*" . $suffix);
    return $files ? $files[0] : null;
}

function extractTimestampFromFilePath($filePath) {
    if (preg_match('/(\d+)_api-cache\.json$/', $filePath, $matches)) {
        return $matches[1]; // The first captured group is the timestamp
    }
    return null; // Return null if no match is found
}


function getMinuteDifference($dateString) {
    $specifiedDate = new DateTime($dateString);
    $serverDate = new DateTime("now");
    return ($specifiedDate->getTimestamp() - $serverDate->getTimestamp()) / 60;
}


// Example usage
$useCache = false;
$cacheFile = findFirstCacheFile();

//echo $cacheFile . "\n\n";
if($cacheFile != null) {
    $nextEventTimeFromCache = extractTimestampFromFilePath($cacheFile);
    $date = new DateTime();
    $date->setTimestamp($nextEventTimeFromCache);
    $timeGapInMinutes = getMinuteDifference($date->format('Y-m-d H:i:s'));
    
    if($timeGapInMinutes > 3) { // 3 Minutes before any event cache is avoided
        $useCache = true;
    }
}


if($useCache) {
    $cacheContent = file_get_contents($cacheFile);
    if($cacheContent !== false) {
        echo $cacheContent;
        exit();
    }
}



$dom = HtmlDomParser::file_get_html("https://sslecal2.forexprostools.com/", false, null, 0);
$elems = $dom->getElementById("#ecEventsTable")->find("tr[id*='eventRowId']");
$data = [];

$nextFutureEvent = null;
$nextFutureEventSmallestDif = 9999;
$nextFutureEventName = "";

foreach($elems as $element) {
	$date = isset($element->attr["event_timestamp"]) ? $element->attr["event_timestamp"] : null;
	$name = count($name = $element->find("td.event")) > 0 ? sanitize($name[0]->text()) : null;

	if($date != null) {
		$minutesDifference = getMinuteDifference($date);
		if($minutesDifference > 0 && ($nextFutureEvent == null || $minutesDifference < $nextFutureEventSmallestDif)) {
			$nextFutureEvent = $date;
			$nextFutureEventSmallestDif = $minutesDifference;
			$nextFutureEventName = $name;
		}
	}
	
    array_push($data, [
        "economy" => sanitize($element->find("td.flagCur")[0]->text()),
        "impact" =>  count($element->find("td.sentiment")[0]->find("i.grayFullBullishIcon")),
	    "date" => $date,
	    "name" => $name,
	    "actual" => count($actual = $element->find("td.act")) > 0 ? sanitize($actual[0]->text()) : null,
	    "forecast" => count($forecast = $element->find("td.fore")) > 0 ? sanitize($forecast[0]->text()) : null,
	    "previous" => count($previous = $element->find("td.prev")) > 0 ? sanitize($previous[0]->text()) : null,
	]);
}

//echo ($nextFutureEventSmallestDif / 60). "\n" . $nextFutureEventName . "\n";
//echo $nextFutureEvent;
$nextFutureEventDate = new DateTime($nextFutureEvent);
echo json_encode($data);
file_put_contents($nextFutureEventDate->getTimestamp() . "_api-cache.json", json_encode($data));
exit();
