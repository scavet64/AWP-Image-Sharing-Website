<?php
function parseTextForUsername($textToParse){
    $regex = "/@{1}([a-zA-Z0-9_-]+)/";
	$textToParse = preg_replace($regex, '<a class="linkToUser" href="user.php?u=$1">$0</a>', $textToParse);
	return($textToParse);
}
?>