<?php

@session_start();
unset($_SESSION['user']);
@session_destroy();

header("Location: http://skills-it.hu/login");