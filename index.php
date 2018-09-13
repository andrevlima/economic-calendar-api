<?php
/**
* Economic Calendar API.
* 
* It uses the investing.com as data source and using a "web crawling" methodology,
* revelant data is captured and returned in a more well-structured data model, in this
* case it will return a JSON.
* 
* There is no guarantees about the availability or estability of this API, changes 
* can be done in source page that can result in a crash of crawler methodology.
*
* @author André Lima <andrelimamail@gmail.com>
* @license MIT
* @version 1.0
*/
header("Content-Type: application/json");

use Sunra\PhpSimple\HtmlDomParser;
include_once("Src\Sunra\PhpSimple\HtmlDomParser.php");

$dom = HtmlDomParser::file_get_html( "https://sslecal2.forexprostools.com/" );

$elems = $dom->getElementById("#ecEventsTable")->find("tr[id*='eventRowId']");
$data = [];

function sanitize($str) {
	return trim(str_replace("&nbsp;", "", $str));
}

foreach($elems as $element) {
    array_push($data, [
        "pair" => sanitize($element->find("td.flagCur")[0]->text()),
        "impact" =>  count($element->find("td.sentiment")[0]->find("i.grayFullBullishIcon")),
	    "data" => isset($element->attr["event_timestamp"]) ? $element->attr["event_timestamp"] : null,
	    "name" => count($name = $element->find("td.event")) > 0 ? sanitize($name[0]->text()) : null,
	    "actual" => 	count($actual = $element->find("td.act")) > 0 ? sanitize($actual[0]->text()) : null,
	    "forecast" => 	count($forecast = $element->find("td.fore")) > 0 ? sanitize($forecast[0]->text()) : null,
	    "previous" => 	count($previous = $element->find("td.prev")) > 0 ? sanitize($previous[0]->text()) : null,
	]);
}

echo json_encode($data);
?>