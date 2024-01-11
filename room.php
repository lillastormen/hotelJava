<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);
?>


<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"><div class="gradientRoom"></div> </section>

<section class="heroRoomPage">
    <h2><?= $room["RoomName"]; ?></h2>
    <h4>Follow the steps below to secure your booking!</h4>
</section>

<!-- Booking form - goes to confirmAndPay.php -->
<form name="mainForm" action="/confirmAndPay.php?id=<?= $roomId?>" method="POST">
    <section class="datesForm">
        <h3>1. Choose the dates of your stay: </h3>
            <label for="arrivalDate">Arrival date: </label>
            <input type="date" id="arrivalDate" name="arrivalDate" min="2024-01-01" max="2024-01-31" required>
            <label for="departureDate">Departure date: </label>
            <input type="date" id="departureDate" name="departureDate" min="2024-01-01" max="2024-01-31" required></br>
    </section>

    <section class="calendarContainer">
        <h3>2. Take a look on the calendar too make sure that the room is avaiable: </h3>
        <div><?php require __DIR__ . '/calendar.php';?></div>
    </section>

    <section class="addFeatures">
        <h3>3. Add extra features to your reservation: </h3>
        <?php
            $features = getFeatures();
            foreach($features as $feature){?>
             <div class="featureContainer">
                <div class="featureCheckbox">
                    <input type="checkbox" id="<?= $feature["FeatureType"]?>" name="features[]" value="<?= $feature["FeatureId"]?>">
                </div>
                <div class="featureInfo">
                    <label for="<?= $feature["FeatureType"]?>"><?= $feature["FeatureType"]?></label>
                    <span><?= $feature["FeaturePrice"] ?>$</span>
                </div>
             </div>
            <?php }?>
    </section>

    <section class="customerDataForm">
        <h3>4. Enter your personal information to complete the booking: </h3>
            <label for="fname">First name: </label>
            <input type="text" id="fname" name="fname"required><br><br>
            <label for="lname">Last name: </label>
            <input type="text" id="lname" name="lname"required><br><br>
            <div class="bookButton">
                <input class="submitBookingButton" type="submit" value="Book">
            </div>
    </section>
</form>

<?php require __DIR__ . '/footer.php';?>