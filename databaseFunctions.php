<?php

//functipon to get the name of the hotel
function getHotel(){
    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT Name FROM Hotel");
    $hotel = $query->fetch(); 
    return $hotel;
}

//function to get a booking ID
function getBookingById($bookingId){
    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT * FROM Bookings WHERE BookingId=" . $bookingId);
    $booking = $query->fetch(); 
    return $booking;
}

//function to change the price of the room
function changeRoomPrice($roomId, $price){
    $db = connect('hotel.sqlite3');
    $query = $db->query("UPDATE Rooms SET RoomPrice =" . $price . " WHERE RoomId=" . $roomId);
    $roomPrice = $query->fetch(); 
    return $roomPrice;
}

//function to create a booking
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

createBooking('Jane', 'dndnndndn', '2024-01-05', '2024-01-06', 3, [2,3]);

//function to get the total amount of days, cost of the room, cost of the features and the total cost of all of these
function calculateTotalCost ($bookingId){
    $db = connect('hotel.sqlite3');
    
    $query = $db->query(
        "SELECT (julianday(bookings.departureDate) - julianday(bookings.arrivalDate)) AS days,
        SUM(rooms.roomPrice * (julianday(bookings.departureDate) - julianday(bookings.arrivalDate))) AS roomCost,
        SUM(features.featurePrice * (julianday(bookings.departureDate) - julianday(bookings.arrivalDate))) AS featureCost
        FROM Rooms, Features
        JOIN Bookings ON Rooms.RoomId = Bookings.RoomId
        JOIN BookedFeatures ON Features.FeatureId = BookedFeatures.Id");
        
        $result = $query->fetch();

        $roomCost = $result['roomCost'];
        $featureCost = $result['featureCost'];

        $totalCost = $roomCost + $featureCost;

        return [
            'roomCost' => $roomCost,
            'featureCost' => $featureCost,
            'totalCost' => $totalCost
        ];

    

}


print_r(calculateTotalCost(1));
?>
