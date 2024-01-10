<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$rooms = getRooms();

?>


<section class="hero" style="background-image: url(/Assets/hotel2.png);">  
    <div class="gradient"></div>     
    <span class="heroSpan">
        <h1>Welcome to Sahaja Resort</h1>
        <h3>Every detail is designed for your comfort, and every moment promises a sense of understated luxury</h3> 
    </span>
</section>



<section id="rooms">
    <div class="block"></div>
    <h2>OUR ROOMS</h2>
    <div class="roomImageContainer">
    <?php
        foreach ($rooms as $room){?>
        <div>
            <div>
                <a href="/room.php?id=1"><img src="/Assets/<?= $room["RoomType"] ?>.png"></a>
            </div>
            <div>
                <h3><?= $room["RoomName"] ?></h3>
            </div>
            <div>
                <h4><b><?=$room["RoomPrice"];?>$</b></h4>
            </div>
            <div>
                <p><?=$room["RoomDescription"];?></p>
            </div>
           
        </div>
        <?php } ?>
    </div>
</section>

<section class="deal lightFont">
    <div class="block"></div>
    <h2>ONLINE BOOKING OFFER</h2>
    <h4>Stay longer than 5 days and unlock a 20% discount, making your retreat even more unforgettable experience.</h4>
    <p>Cooming soon!</p>
</section>

<section class="features">
    <div class="block"></div>
    <h2>FEATURES</h2>
    <div class="featureImageContainer">
        <div>
            <img src="/Assets/breakfast.png">
        </div>
        <div>
            <img src="/Assets/swimmingpool.png">
        </div>
        <div>
            <img src="/Assets/minibar.png">
        </div>
    </div>
</section>


<section class="info lightFont">
    <h4>Our resort offers not just accommodation, but an escape into a world where the pace of life harmonizes with the rhythmic pulse of the forest. Whether it's a leisurely stroll through the jungle trails, a refreshing dip in natural springs, or simply unwinding on your private balcony,every moment is an invitation to connect with the authentic spirit of Indonesia.</h4>
</section>
<section class="breakBlock"></section>



<?php require __DIR__ . '/footer.php';?>