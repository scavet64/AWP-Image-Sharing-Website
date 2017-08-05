<?php
function parseTextForHashtag($textToParse){
    $regex = "/#+([a-zA-Z0-9_]+)/";
	$textToParse = preg_replace($regex,
		'<a class="hashtag" href="hashtag.php?tag=$1">$0</a>', $textToParse);
	return($textToParse);
}
?><?php
function getHashtagArray($textToParse){
    $regex = "/#+([a-zA-Z0-9_]+)/";
	preg_match_all($regex, $textToParse, $matches);
	return($matches);
}
?>