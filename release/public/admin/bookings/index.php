<?php

@session_start();
if ( !isset($_SESSION['user']) || $_SESSION['user'] !== "admin" )
    header("Location: http://skills-it.hu/login");

$db = new PDO(
    "mysql:host=127.0.0.1;dbname=skills_it_04;charset=utf8",
    "skill17",
    "Shanghai2022"
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <script src="../../assets/jquery/jquery-3.6.0.min.js"></script>
    <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
    <title>Accomodations</title>
</head>
<body class="login">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link menu-item" href="http://skills-it.hu/admin/accomodations">Accomodations<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
            <a class="nav-link menu-item menu-active" href="http://skills-it.hu/admin/bookings">Bookings<span class="sr-only">(current)</span></a>
        </li>
        <?php @session_start(); if (isset($_SESSION['user']) &&  $_SESSION['user'] == "admin") echo '<li class="nav-item active">
            <a class="nav-link menu-item" href="http://skills-it.hu/logout">Logout<span class="sr-only">(current)</span></a>
        </li>'; ?>
        </ul>
    </div>
    </nav>

    <div class="content">
        <form class="search">
            <input name="comment" type="text" class="form-control" placeholder="Search in the comments..." <?php if (isset($_GET['comment'])) echo 'value="'.$_GET['comment'].'"'; ?>>
            <input type="submit" class="btn btn-primary" value="Go"></button>
    </form>

        <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Accomodation ID</th>
                <th scope="col">Accomodation name</th>
                <th scope="col">Check-in Date</th>
                <th scope="col">Check-out Date</th>
                <th scope="col">Booking date</th>
                <th scope="col">comment</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ( isset($_GET['comment']) )
                {
                    $query = $db->prepare("SELECT * FROM bookings WHERE comment LIKE :comment");
                    $str = "%{$_GET['comment']}%";
                    $query->bindParam(":comment", $str );
                }
                else 
                {
                    $query = $db->prepare("SELECT * FROM bookings b JOIN accomodations a on b.accomodation_id = a.id");
                }
                $query->execute();

                foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
                {
                    echo "<tr>
                    <th>{$res['id']}</th>
                    <td>{$res['accomodation_id']}</td>
                    <td>{$res['name']}</td>
                    <td>{$res['check_in_date']}</td>
                    <td>{$res['check_out_date']}</td>
                    <td>{$res['booking_date']}</td>
                    <td>{$res['comment']}</td>
                </tr>";
                }
            ?>
        </tbody>
        </table>
    </div>
</body>
</html>