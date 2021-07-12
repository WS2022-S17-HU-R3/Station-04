<?php
require_once 'app.php';

header("Content-Type: application/json;charset=UTF-8");

$uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$request = [
    "url" => array_splice( $uri, 2 ),
    "params" => $_REQUEST,
    "method" => $_SERVER['REQUEST_METHOD']
];

$app = new Router( $request );
$app->dbConnect([
    "host" => "127.0.0.1",
    "username" => "skill17",
    "password" => "Shanghai2022",
    "db" => "skills_it_04"
]);

require_once 'routes.php';

$app->run();