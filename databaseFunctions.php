<?php
 
//functipon to get the name of the hotel
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

//function to check if dates of bookings are not overlaping before making a booking
function checkRoomAvailability($roomId, $arrivalDate, $departureDate){

    $db = connect('hotel.sqlite3');
    $query = $db->query(
        "SELECT RoomId
        FROM Bookings
        WHERE RoomId = :roomId
        AND 
        (
	        (DepartureDate > :arrivalDate AND DepartureDate < :departureDate)
	        OR
			(ArrivalDate > :arrivalDate AND ArrivalDate < :departureDate)
		)"
    );
    
    $query->bindParam(':roomId', $roomId, PDO::PARAM_INT);
    $query->bindParam(':arrivalDate', $arrivalDate, PDO::PARAM_STR);
    $query->bindParam(':departureDate', $departureDate, PDO::PARAM_STR);


    $result = $query->fetch();

    return empty($result);
}





//function to create a booking
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
        return "Room unavailable";
    }
   
}

//creating a function for getting the booking info
function getBooking($bookingId){
    return json_encode([
        ...getHotel(),
        ...calculateTotalCost($bookingId),
        ...getBookingFeatures($bookingId)
    ]);  
}

//print_r(createBooking('Mary', 'Jane', '2024-01-01', '2024-01-05', 1, [1,2]));
//print_r(getBooking(10));

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

//print_r(calculateTotalCost(1));

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
        WHERE r.roomId = :roomId"
    );

    $query->bindParam(':roomId', $roomId, PDO::PARAM_INT);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}


?>


