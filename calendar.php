<?php require 'vendor/autoload.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/databaseFunctions.php';

use benhall14\phpCalendar\Calendar as Calendar;

$calendarBookings = getBookingsForCalendar(1);

$calendar = new Calendar;
$calendar->stylesheet();


foreach($calendarBookings as $booking){
    $calendar->addEvent(date($booking['ArrivalDate']), date($booking['DepartureDate']), $booking['RoomName'], true);
}

echo $calendar->draw(date('Y-1-1'), ''); 

?>