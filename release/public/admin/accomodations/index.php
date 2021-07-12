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
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link menu-item menu-active" href="http://skills-it.hu/admin/accomodations">Accomodations<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
            <a class="nav-link menu-item" href="http://skills-it.hu/admin/bookings">Bookings<span class="sr-only">(current)</span></a>
        </li>
        <?php @session_start(); if (isset($_SESSION['user']) &&  $_SESSION['user'] == "admin") echo '<li class="nav-item active">
            <a class="nav-link menu-item" href="http://skills-it.hu/logout">Logout<span class="sr-only">(current)</span></a>
        </li>'; ?>
        </ul>
    </div>
    </nav>

    <div class="content">
        <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Description</th>
            <th scope="col">Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = $db->prepare("SELECT * FROM accomodations");
                $query->execute();

                foreach($query->fetchAll(PDO::FETCH_ASSOC) as $res)
                {
                    echo "<tr>
                    <th>{$res['id']}</th>
                    <td>{$res['name']}</td>
                    <td>{$res['price']}</td>
                    <td>{$res['description']}</td>
                    <td><button class='btn btn-warning'>View</button></td>
                </tr>";
                }
            ?>
        </tbody>
        </table>
    </div>
</body>
</html>