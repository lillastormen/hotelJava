<?php 
require __DIR__ . '/header.php';
require __DIR__ . '/databaseFunctions.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : '';
$room = getARoomById($roomId);

?>

<section class="hero" style="background-image: url(/Assets/<?= $room["RoomType"] ?>.png);"></section>

<section class="heroSpan"><h2><?= $room["RoomName"] ?></h2></section>

<section class="datesForm"></section>

<?php require __DIR__ . '/calendar.php';?>

<section class="customerDataForm"></section>

<?php require __DIR__ . '/footer.php';?>