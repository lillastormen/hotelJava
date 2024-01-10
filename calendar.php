<?php 

declare(strict_types=1);

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

<link rel="stylesheet" href="/Styles/calendar.css">

<?= $calendar->draw(date('Y-1-1'), '');

?>


        