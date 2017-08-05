<?php
function convertDate($dateToConvert, $TimezoneToSet){
    $original_datetime = $dateToConvert;
    
    //Since the MySQL server on elvis uses EDT as its default timezone, this is wrong.
    //$original_timezone = new DateTimeZone('UTC');
    $original_timezone = new DateTimeZone('EDT');
    
    // Instantiate the DateTime object, setting it's date, time and time zone.
    $datetime = new DateTime($original_datetime, $original_timezone);
    
    // Set the DateTime object's time zone to convert the time appropriately.
    $target_timezone = new DateTimeZone($TimezoneToSet);
    $datetime->setTimeZone($target_timezone);
    
    // Outputs a date/time string based on the time zone you've set on the object.
    return $datetime->format('m/d/y - h:i A T');
}
?>