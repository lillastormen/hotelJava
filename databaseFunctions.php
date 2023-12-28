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

function createBooking ($guestName, $guestSurname, $arrivalDate, $departureDate, $roomId, $featuresIdArray){ 
    $db = connect('hotel.sqlite3');

    $query = $db->query(
        "INSERT INTO Bookings (GuestName, GuestSurname, ArrivalDate, DepartureDate, RoomId) 
        VALUES ('$guestName', '$guestSurname', '$arrivalDate', '$departureDate', '$roomId')");
    $bookingId = $db->lastInsertId(); //gets the ID of the last booking 


    if(count($featuresIdArray)) {

        $bookedFeaturesString = "";

        foreach($featuresIdArray as $featureId){
            $bookedFeaturesString .= "(".$bookingId.",".$featureId."),"; 
            // .= glues together the left and the right string
        }

        $query = $db->query(
            "INSERT INTO BookedFeatures (BookingId, FeatureId) VALUES ". substr($bookedFeaturesString, 0, -1));
    }
}

//createBooking('Marta', 'Kowalska', '2024-01-05', '2024-01-06', 1, [1,2]);

function calculateTotalCost ($bookingId){
    $db = connect('hotel.sqlite3');
    
    $query = $db->query(
    "SELECT julianday(bookings.departureDate) - julianday(bookings.arrivalDate) AS days,
    SUM(rooms.roomPrice * (julianday(bookings.departureDate) - julianday(bookings.arrivalDate))) AS roomCost
    FROM Rooms
    JOIN Bookings ON Rooms.RoomId = Bookings.RoomId;");
    $roomCost = $query->fetch(); 
    return $roomCost;
}


print_r(calculateTotalCost(1));
?>
