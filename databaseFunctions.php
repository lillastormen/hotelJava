<?php

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
    $query = $db->query("UPDATE Rooms SET RoomPrice =" . $price . " WHERE RoomId=" . $roomId);

}

function createBooking ($guestName, $guestSurname, $arrivalDate, $departureDate){
    $db = connect('hotel.sqlite3');
    $query = $db->query("INSERT INTO Bookings (GuestName, GuestSurname, ArrivalDate, DepartureDate) VALUES ( '$guestName', '$guestSurname', '$arrivalDate', '$departureDate')");
}


$hotel = getHotel();

createBooking ('Karolina', 'Limanowska', '2024-01-15', '2024-01-17');

?>
