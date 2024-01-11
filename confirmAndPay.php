<?php 

require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$roomId = isset($_POST['id']) ? $_POST['id'] : $roomId;
$room = getARoomById($roomId);
$bookingSuccessfull = false;


// Adds booking to database, checks if booking contains all the required information. If it does, it creates an unpaid booking
// If something is missing or dates are wrong it alerts the user and goes back to room.php
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


// Checks if payment details are POSTed. If they are they we try to use them to pay for booking
// 1.Validate the transfer code (useTransferCode() in functions.php, first part)
// 2. Deposit the transfer code into our bank account (useTransferCode() in functions.php, second part)
// 3. Update booking in the DB (transfer code, paid, totalCost) (payForBooking() in databaseFunctions.php - this one also calls step 1 )
// Doing payment and updating the booking in the database
if (
    isset($_POST["transferCode"]) && 
    isset($_POST["totalCost"]) && 
    isset($_POST["bookingId"]) 
) {
    $bookingPaid = payForBooking($_POST["bookingId"], $_POST["transferCode"], $_POST["totalCost"]);
    
    if(!$bookingPaid){?>
        <script>alert("Invalid transfer code, please try again"); history.back();</script>
    <?php } else { 
        $bookingSuccessfull = true;
        $bookingResponse = getBooking($_POST["bookingId"]);
    }
}

if($bookingSuccessfull) { ?>
    <section class="succesfullPayment">
        <div>
            <h2>Payment succesfull! Welcome to Sahaja Resort!</h2>
            <div><?= print_r($bookingResponse); ?></div>
        </div>
    </section>
<?php } else { ?>
<section class="hero" style="background-image: url(Assets/<?= $room["RoomType"] ?>.png);"><div class="gradientRoom"></div></section>

<section class="heroPage">
    <h2>Confirm and Pay for your booking of: <?= $room["RoomName"]; ?></h2>
    <form action="confirmAndPay.php?roomId=<?= $roomId ?>" method="POST">
        <label for="transferCode">Transfer Code: </label><br><br>
        <input type="text" id="transferCode" name="transferCode" required><br><br>
        <label for="totalCost">Total Cost: </label><br><br>
        <input type="text" id="totalCost" name="totalCost" readonly value="<?= $booking->totalCost; ?>"/><br><br>
        <input type="hidden" id="bookingId" name="bookingId" readonly value="<?= $booking->bookingId; ?>"/>
        <input type="hidden" id="roomId" name="roomId" readonly value="<?= $roomId; ?>"/>
        <div class="confirmAndPaYButtonContainer">
            <input class="submitConfirmAndPayButton" type="submit" value="Confirm and Pay" />
        </div>
    </form>
</section>

<?php
}
require __DIR__ . '/footer.php'; ?>

