<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$roomId = isset($_POST['id']) ? $_POST['id'] : $roomId;
$room = getARoomById($roomId);
$bookingSuccessfull = false;

if(
    isset($_POST["fname"]) && 
    isset($_POST["lname"]) && 
    isset($_POST["arrivalDate"]) && 
    isset($_POST["departureDate"])
) {
    $features = isset($_POST["features"]) ? $_POST["features"] : [];
    $booking = json_decode(createBooking($_POST["fname"], $_POST["lname"], $_POST["arrivalDate"], $_POST["departureDate"], $roomId, $features));
   if(!$booking){ ?>  
        <script>alert("Booking unsuccessful! Choose different dates and try again!"); history.back();</script>
        <?php exit(); 
    }
}

if (
    isset($_POST["transferCode"]) && 
    isset($_POST["totalCost"]) && 
    isset($_POST["bookingId"]) 
) {
    $bookingPayed = payForBooking($_POST["bookingId"], $_POST["transferCode"], $_POST["totalCost"]);
    
    if(!$bookingPayed){?>
        <script>alert("Invalid transfer code, please try again"); history.back();</script>
    <?php } else { 
        $bookingSuccessfull = true;
        $bookingResponse = getBooking($_POST["bookingId"]);
    }
}

if($bookingSuccessfull) { ?>
    <section class="heroRoomPage">
        <h2>Payment succesfull! Welcome to Sahaja Resort!</h2>
        <?= print_r($bookingResponse); ?>
    </section>
<?php } else { ?>
<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>
<div class="gradientRoom"></div>
<section class="heroRoomPage">
    <h2>Confirm and Pay for your booking of: <?= $room["RoomName"]; ?></h2>
    <form action="confirmAndPay.php?roomId=<?= $roomId ?>" method="POST">
        <label for="transferCode">Transfer Code: </label><br><br>
        <input type="text" id="transferCode" name="transferCode" required><br><br>
        <label for="totalCost">Total Cost: </label><br><br>
        <input type="text" id="totalCost" name="totalCost" readonly value="<?= $booking->totalCost; ?>"/><br><br>
        <input type="hidden" id="bookingId" name="bookingId" readonly value="<?= $booking->bookingId; ?>"/>
        <input type="hidden" id="roomId" name="roomId" readonly value="<?= $roomId; ?>"/>
        <input type="submit" value="Confirm and Pay" />
    </form>
</section>
<?php
}
require __DIR__ . '/footer.php'; ?>