<?php
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
    {
        if ( isset($_REQUEST['username']) && isset($_REQUEST['password']) )
        {
            if ( $_REQUEST['username'] == 'admin' && $_REQUEST['password'] == '123' )
            {
                @session_start();
                $_SESSION['user'] = 'admin';
                header("Location: http://skills-it.hu/admin/accomodations");
            }
            else
            {
                echo '<div class="alert-container"><div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Wrong username or password.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div></div>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/jquery/jquery-3.6.0.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <title>Login</title>
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
        <a class="nav-link menu-item" href="#">Accomodations<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link menu-item" href="#">Bookings<span class="sr-only">(current)</span></a>
      </li>
      <?php @session_start(); if (isset($_SESSION['user']) &&  $_SESSION['user'] == "admin") echo '<li class="nav-item active">
        <a class="nav-link menu-item" href="#">Logout<span class="sr-only">(current)</span></a>
      </li>'; ?>
    </ul>
  </div>
</nav>

    <div class="login-container">
        <form action="#" method="POST" class="login-modal">
            <h2>Admin login</h2>
            <label for="username">Username</label>
            <input class="form-control" id="username" type="text" name="username">
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password">
            <input class="btn btn-primary" id="submit" type="submit" name="submit" value="Login">
        </form>
    </div>
</body>
</html>