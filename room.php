<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);

?>

<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>

<div class="gradientRoom"></div> 

<section class="heroRoomPage lightFont">
    <h2><?= $room["RoomName"] ?></h2>
    <p>Follow the steps below to secure your booking!</p>
</section>

<div class="block"></div>

<section class="datesForm">
    <h3>Choose the dates for your stay: </h3>
    <form action>
        <label for="arrivalDate">Arrival date: </label>
        <input type="date" id="arrivalDate" name="arrivalDate" min="2024-01-01" max="2024-01-31">
        <label for="departureDate">Departure date: </label>
        <input type="date" id="departureDate" name="departureDate" min="2024-01-01" max="2024-01-31"></br>
        <div class="datesButton">
            <input class="submitDatesButton" type="submit">
        </div>
    </form>
</section>

<section class="calendarContainer">
    <h3>Avaiability of the room: </h3>
    <div><?php require __DIR__ . '/calendar.php';?></div>
</section>

<section class="addFeatures">
    <h3>Add extra features to your reservation: </h3>
    <form class="featuresForm" action>
        <input type="checkbox" id="breakfast" name="breakfast" value="Breakfast">
        <label for="breakfast">Breakfast</label><br>
        <input type="checkbox" id="swimmingpool" name="swimmingpool" value="Swimmingpool">
        <label for="swimmingpool">Swimmingpool</label><br>
        <input type="checkbox" id="minibar" name="minibar" value="Minibar">
        <label for="minibar">Minibar</label><br><br>
        <div class="featuresButton">
            <input class="submitFeaturesButton" type="Submit">
        </div>
    </form>
</section>

<section class="customerDataForm">
    <h3>Enter your personal information to complete the booking:</h3>
    <form name="myForm" action="/action_page.php" method="get">
        <label for="fname">First name: </label>
        <input type="text" id="fname" name="fname"><br><br>
        <label for="lname">Last name: </label>
        <input type="text" id="lname" name="lname"><br><br>
        <input type="button" onclick="formSubmit()" value="Send form data!">
    </form>
</section>

<section class="bookingConfirmation">

</section>

<?php require __DIR__ . '/footer.php';?>