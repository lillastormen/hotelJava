<?php

declare(strict_types=1);

// Function to get the name of the hotel
function getHotel(){

    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT * FROM Hotel");
    
    $result = $query->fetch(); 
    $hotel = $result['Name'];
    $island = $result['Island'];
    $stars = $result['Stars'];

    return [
        'Hotel' => $hotel,
        'Island' => $island,
        'Stars' => $stars
    ];
}

function getBookingDates($bookingId){

    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT DepartureDate, ArrivalDate FROM Bookings WHERE BookingId = ". $bookingId);
    
    $result = $query->fetch(); 
    $arrivalDate = $result['ArrivalDate'];
    $departureDate = $result['DepartureDate'];

    return [
        "arrival_date" => $arrivalDate,
        "departure_date" => $departureDate,
    ];
}


// Function to get a booking ID
function getBookingById($bookingId){
    
    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT * FROM Bookings WHERE BookingId=" . $bookingId);
    
    $booking = $query->fetch(); 
    return $booking;
}

//print_r(getBookingById(4));


// Function to change the price of the room
function changeRoomPrice($roomId, $price){
    
    $db = connect('hotel.sqlite3');
    $query = $db->query("UPDATE Rooms SET RoomPrice =" . $price . " WHERE RoomId=" . $roomId);
    
    $roomPrice = $query->fetch(); 
    return $roomPrice;
}


// Function to check if dates of bookings are not overlaping before making a booking
function checkRoomAvailability($roomId, $arrivalDate, $departureDate){

    $db = connect('hotel.sqlite3');
    $query = $db->query("SELECT RoomId
    FROM Bookings
    WHERE RoomId = $roomId
    AND Paid = true
    AND (
        (ArrivalDate >= '$arrivalDate' AND ArrivalDate <= '$departureDate')
    OR
        (DepartureDate >= '$arrivalDate' AND DepartureDate <= '$departureDate')
    OR
        (ArrivalDate <= '$arrivalDate' AND DepartureDate >= '$departureDate')
    )");
 
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return empty($result);
}


// Function to create a booking
function createBooking ($guestName, $guestSurname, $arrivalDate, $departureDate, $roomId, $featuresIdArray){ 

    if(checkRoomAvailability($roomId, $arrivalDate, $departureDate)){
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
        return getBooking($bookingId);
    } else {
        return false;
    }   
}

function payForBooking($bookingId, $transferCode, $totalCost){
    // Function that uses the transfer code at the bank, returns false if the transfer code is invalid
    // This function is included in functions.php
    if(useTransferCode($transferCode, $totalCost)){ 
        $db = connect('hotel.sqlite3');
        $query = $db->query(
            "UPDATE Bookings 
                SET 
                    Paid = true,
                    TransferCode = '$transferCode',
                    TotalCost = '$totalCost'
                WHERE 
                    bookingId = $bookingId");
            return true;
    } else {
        return false;
    }
}

// Function for getting the booking info
function getBooking($bookingId){
    return json_encode([
        "bookingId" => $bookingId,
        ...getHotel(),
        ...getBookingDates($bookingId),
        ...calculateTotalCost($bookingId),
        ...getBookingFeatures($bookingId)
    ]);  
}

/* function getBooking($bookingId)
{
    $bookingDetails = getBookingById($bookingId, ['GuestName', 'GuestSurname', 'ArrivalDate', 'DepartureDate']);
    $totalCostDetails = calculateTotalCost($bookingId);
    $bookingFeatures = getBookingFeatures($bookingId);

    $bookingData = [
        "bookingId" => $bookingId,
        "hotel" => getHotel(),
        "guestDetails" => [
            "guestName" => $bookingDetails['GuestName'],
            "guestSurname" => $bookingDetails['GuestSurname'],
            "arrivalDate" => $bookingDetails['ArrivalDate'],
            "departureDate" => $bookingDetails['DepartureDate']
        ],
        "totalCost" => [
            "roomCost" => $totalCostDetails['roomCost'],
            "featureCost" => $totalCostDetails['featureCost'],
            "totalCost" => $totalCostDetails['totalCost']
        ],
        "bookingFeatures" => $bookingFeatures
    ];

    return json_encode($bookingData);
}*/

//print_r(createBooking('Mary', 'Jane', '2024-01-01', '2024-01-05', 1, [1,2]));
//print_r(getBooking(10));

// Function to get the total amount of days, cost of the room, cost of the features and the total cost of all of these
function calculateTotalCost ($bookingId){
    
    $db = connect('hotel.sqlite3');
    
    $query = $db->query(
        "SELECT (julianday(bookings.departureDate) - julianday(bookings.arrivalDate)) AS days,
        (rooms.roomPrice * (julianday(bookings.departureDate) - julianday(bookings.arrivalDate))) AS roomCost,
        (features.featurePrice * (julianday(bookings.departureDate) - julianday(bookings.arrivalDate))) AS featureCost
        FROM Rooms, Features
        JOIN Bookings ON Rooms.RoomId = Bookings.RoomId
        JOIN BookedFeatures ON Features.FeatureId = BookedFeatures.Id
        WHERE Bookings.BookingId = $bookingId");
        
    $result = $query->fetch();

    $roomCost = ($result['roomCost']) ? $result['roomCost'] : 0;
    $featureCost = ($result['featureCost']) ? $result['featureCost'] : 0;
    // Adding the discount
    $stayDuration = $result['days'];

    if ($stayDuration >= 5){
        $roomCost -= $result['roomCost'] / $stayDuration;
    }

    $totalCost = $roomCost + $featureCost;

    return [
        'roomCost' => $roomCost,
        'featureCost' => $featureCost,
        'totalCost' => $totalCost
    ];
}

//print_r(calculateTotalCost(1));

// Function to fetch the booked features
function getBookingFeatures($bookingId){
    $db = connect('hotel.sqlite3');

    $query = $db->query(
       "SELECT Features.FeatureType, Features.FeaturePrice 
       FROM Features
       JOIN BookedFeatures ON Features.FeatureId = BookedFeatures.FeatureId
       JOIN Bookings ON Bookings.BookingId = BookedFeatures.BookingId
       WHERE Bookings.BookingId = $bookingId");

    $results = $query->fetchAll();


    $featuresInfo = [];
    foreach ($results as $result) {
        $featureType = $result['FeatureType'];
        $featurePrice = $result['FeaturePrice'];

        $featuresInfo[] = [
            'FeatureType' => $featureType,
            'FeaturePrice' => $featurePrice
        ];
    }
    return ['features' => $featuresInfo];  
}

//print_r(getBookingFeatures(10));


function getBookingsForCalendar($roomId){
    $db = connect('hotel.sqlite3');

    $query = $db->query(
        "SELECT r.RoomName, b.ArrivalDate, b.DepartureDate
        FROM Rooms r
        JOIN Bookings b ON r.roomId = b.roomId
        WHERE r.roomId = :roomId AND b.Paid = true"
    );

    $query->bindParam(':roomId', $roomId, PDO::PARAM_INT);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}


function getARoomById($roomId){
    $db = connect('hotel.sqlite3');

    $query = $db->prepare("SELECT * 
    FROM Rooms  
    WHERE RoomId = :roomId");

    $query->bindParam(':roomId', $roomId, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetch(PDO::FETCH_ASSOC);

    $db =null; 

    return $results;
}


//f Function to get price of a single feature
function getFeaturePrice($featureId){
    $db = connect('hotel.sqlite3');

    $query = $db->prepare("SELECT FeaturePrice
    FROM Features
    WHERE featureId = :featureId");

    $query->bindParam(':featureId', $featureId, PDO::PARAM_INT);

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    $db =null;

    return $result['FeaturePrice'];
}

// Function to get the features
function getFeatures(){

    $db = connect('hotel.sqlite3');

    $query = $db->prepare("SELECT * FROM features");
    $query->execute();
    $result = $query->fetchAll();
    
    return $result;
}

// Function the get the room info
function getRooms(){

    $db = connect('hotel.sqlite3');
    
    $query = $db->prepare("SELECT * FROM Rooms");
    $query->execute();
    $result = $query->fetchAll();

    return $result;
}

?>


