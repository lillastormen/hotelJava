<?php 
require_once 'vendor/autoload.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/databaseFunctions.php';

use benhall14\phpCalendar\Calendar as Calendar;

$calendarBookings = getBookingsForCalendar($roomId);

$calendar = new Calendar;
$calendar->stylesheet();

foreach($calendarBookings as $booking){
    $calendar->addEvent(date($booking['ArrivalDate']), date($booking['DepartureDate']), $booking['RoomName'], true);
}
?>

<h3>Room calendar</h3>
<?= $calendar->draw(date('Y-1-1'), ''); ?>


        