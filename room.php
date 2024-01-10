<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);
$errorMessage = "";

if(
    isset($_POST["fname"]) && 
    isset($_POST["lname"]) && 
    isset($_POST["arrivalDate"]) && 
    isset($_POST["departureDate"]) && 
    isset($_POST["features"]) 
) {
   $booking = createBooking($_POST["fname"], $_POST["lname"], $_POST["arrivalDate"], $_POST["departureDate"], $roomId, $_POST["features"]);
   print_r($booking);
   if(!$booking){
?>  

<script>alert("Booking unsuccessful! Choose different dates and try again!");</script>

<?php
    }
}
?>


<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>

<div class="gradientRoom"></div> 

<section class="heroRoomPage">
    <h2><?= $room["RoomName"] ?></h2>
    <h4>Follow the steps below to secure your booking!</h4>
</section>

<?= $errorMessage?>

<form name="mainForm" action="/room.php?id=<?= $roomId?>" method="POST">
    <section class="datesForm">
        <h3>1. Choose the dates for your stay: </h3>
            <label for="arrivalDate">Arrival date: </label>
            <input type="date" id="arrivalDate" name="arrivalDate" min="2024-01-01" max="2024-01-31" required>
            <label for="departureDate">Departure date: </label>
            <input type="date" id="departureDate" name="departureDate" min="2024-01-01" max="2024-01-31" required></br>
    </section>

    <section class="calendarContainer">
        <h3>2. Take a look on the calendar too see if the room is avaiable: </h3>
        <div><?php require __DIR__ . '/calendar.php';?></div>
    </section>

    <section class="addFeatures">
        <h3>3. Add extra features to your reservation: </h3>
            <input type="checkbox" id="breakfast" name="features[]" value="1">
            <label for="breakfast">Breakfast</label><br>
            <input type="checkbox" id="swimmingpool" name="features[]" value="3">
            <label for="swimmingpool">Swimmingpool</label><br>
            <input type="checkbox" id="minibar" name="features[]" value="2">
            <label for="minibar">Minibar</label><br><br>
    </section>

    <section class="customerDataForm">
        <h3>4. Enter your personal information to complete the booking:</h3>
            <label for="fname">First name: </label>
            <input type="text" id="fname" name="fname"required><br><br>
            <label for="lname">Last name: </label>
            <input type="text" id="lname" name="lname"required><br><br>
            <div class="bookButton">
                <input class="submitBookingButton" type="submit" value="Book">
            </div>
    </section>
</form>

<section class="bookingConfirmation">

</section>

<?php require __DIR__ . '/footer.php';?>