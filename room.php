<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);

?>

<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>

<section class="heroSpan"><h2><?= $room["RoomName"] ?></h2></section>

<section class="datesForm">
    <form action>
        <label for="arrivalDate">Arrival date: </label>
        <input type="date" id="arrivalDate" name="arrivalDate">
        <label for="departureDate">Departure date: </label>
        <input type="date" id="departureDate" name="departureDate">
        <input type="submit">
    </form>
</section>

<?php require __DIR__ . '/calendar.php';?>

<section class="addFeatures"></section>

<section class="customerDataForm"></section>

<section class="bookingConfirmation"></section>

<?php require __DIR__ . '/footer.php';?>