<?php

$app->register("GET", "/test", function() {
    return "alma";
});

$app->register("GET", "/accomodations", function($request, $db) {
    $query = $db->prepare("SELECT * FROM accomodations");
    $query->execute();

    $response = [];

    foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $result )
    {
        array_push($response, [
            "id" => intval($result["id"]),
            "name" => $result["name"],
            "price" => intval($result["price"]),
            "description" => $result["description"],
            "img" => $result["img"]
        ]);
    }

    return $response;
});

$app->register("GET", "/bookings", function($request, $db) {
    $queryString = array_key_exists( "comment", $request["params"] ) ? "SELECT * FROM bookings WHERE comment LIKE :comment" : "SELECT * FROM bookings";
    $query = $db->prepare($queryString);

    if ( array_key_exists( "comment", $request["params"] ) )
    {
        $str = "%{$request["params"]["comment"]}%";
        $query->bindParam( ":comment", $str );
    }

    $query->execute();

    $response = [];

    foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $result )
    {
        array_push($response, [
            "id" => intval($result["id"]),
            "accomodationId" => intval($result["accomodation_id"]),
            "checkIn" => date($result["check_in_date"]),
            "checkOut" => date($result["check_out_date"]),
            "bookingDate" => date($result["booking_date"]),
            "comment" => $result["comment"]
        ]);
    }

    return $response;
});

$app->register("GET", "/accomodations/{id}/bookings", function($request, $db) {
    $query = $db->prepare("SELECT * FROM bookings WHERE accomodation_id = :acid");
    $query->bindParam( ":acid", $request["url"][1] );
    $query->execute();

    $response = [];

    foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $result )
    {
        array_push($response, [
            "id" => intval($result["id"]),
            "accomodationId" => intval($result["accomodation_id"]),
            "checkIn" => date($result["check_in_date"]),
            "checkOut" => date($result["check_out_date"]),
            "bookingDate" => date($result["booking_date"]),
            "comment" => $result["comment"]
        ]);
    }

    return $response;
});

$app->register("POST", "/bookings", function($request, $db) {
    if ( !array_key_exists("accomodationId", $request["params"]) ||
            !array_key_exists("checkIn", $request["params"])     ||
            !array_key_exists("checkOut", $request["params"])    ||
            !array_key_exists("bookingDate", $request["params"]) ||
            !array_key_exists("comment", $request["params"]) 
        )
    {
        header('HTTP/1.1 406 Wrong Request Body');
        exit();
    }

    $query = $db->prepare("SELECT id FROM accomodations WHERE id = :id");
    $query->bindParam(":id", $request["params"]["accomodationId"]);
    $query->execute();

    if ( count($query->fetchAll()) !== 1 )
    {
        header('HTTP/1.1 407 Wrong Accomodation Id');
        exit();
    }

    $query = $db->prepare("SELECT * FROM bookings WHERE accomodation_id = :id and (:userCheckIn BETWEEN check_in_date and check_out_date OR :userCheckOut BETWEEN check_in_date and check_out_date)");
    $query->bindParam(":id", $request["params"]["accomodationId"]);
    $query->bindParam(":userCheckIn", $request["params"]["checkIn"]);
    $query->bindParam(":userCheckOut", $request["params"]["checkOut"]);
    $query->execute();

    if ( count($query->fetchAll()) > 0 )
    {
        header('HTTP/1.1 408 Booking Is Not Available');
        exit();
    }

    $query = $db->prepare("INSERT INTO bookings (accomodation_id, check_in_date, check_out_date, booking_date, comment) VALUES (:acc, :cin, :cout, :book, :comment)");
    $query->bindParam(":acc", $request["params"]["accomodationId"]);
    $query->bindParam(":cin", $request["params"]["checkIn"]);
    $query->bindParam(":cout", $request["params"]["checkOut"]);
    $query->bindParam(":book", $request["params"]["bookingDate"]);
    $query->bindParam(":comment", $request["params"]["comment"]);
    $query->execute();

    $query = $db->prepare( "SELECT * FROM bookings WHERE id = :id" );
    $lastId = $db->lastInsertId();
    $query->bindParam(":id", $lastId);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC)[0];
});

$app->register("DELETE", "/bookings/{id}", function($request, $db) {
    $query = $db->prepare("SELECT * from bookings WHERE id = :id AND booking_date <= date_add(CURRENT_DATE, INTERVAL 3 DAY)");
    $query->bindParam(":id", $request["url"][1]);
    $query->execute();

    if ( count($query->fetchAll()) < 1 )
    {
        header('HTTP/1.1 406 Booking Cant Be Deleted');
        exit();
    }

    $query = $db->prepare("DELETE FROM bookings WHERE id = :id");
    $query->bindParam(":id", $request["url"][1]);
    $query->execute();

    return true;
});