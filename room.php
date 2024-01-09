<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);

?>

<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>

<section class="heroSpan"><h2><?= $room["RoomName"] ?></h2></section>

<section class="datesForm">
    <h3>Choose the dates for your stay: </h3>
    <form action >
        <label for="arrivalDate">Arrival date: </label>
        <input type="date" id="arrivalDate" name="arrivalDate">
        <label for="departureDate">Departure date: </label>
        <input type="date" id="departureDate" name="departureDate"></br>
        <div class="datesButton">
            <input class="submitDatesButton" type="submit">
        </div>
    </form>
</section>

<section class="calendarContainer">
    <h3>Avaiability of the room: </h3>
    <div><?php require __DIR__ . '/calendar.php';?></div>
</section>

<section class="addFeatures"></section>

<section class="customerDataForm"></section>

<section class="bookingConfirmation"></section>

<?php require __DIR__ . '/footer.php';?>