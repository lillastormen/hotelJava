<?php

declare(strict_types=1);

require __DIR__ . '/functions.php';


function getHotel(){
    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT Name FROM Hotel");
    $hotel = $query->fetch(); 
    return $hotel;
}

function getBookingById($bookingId){
    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT * FROM Bookings WHERE BookingId=" . $bookingId);
    $hotel = $query->fetch(); 
    return $hotel;
}

function changeRoomPrice($roomId, $price){
    $db = connect('hotel.sqlite3');
    $query = $db->query("UPDATE Rooms SET Price =" . $price . " WHERE . RoomId=" . $roomId);

}


$hotel = getHotel();

$booking = getBookingById(1);

print_r($booking);
?>