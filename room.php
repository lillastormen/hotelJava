<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);

?>

<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);">  
    <div class="gradient"></div>     
    <span class="heroSpan"><?= $room["RoomName"] ?></span>
</section>

<?php require __DIR__ . '/calendar.php';?>

<?php require __DIR__ . '/footer.php';?>