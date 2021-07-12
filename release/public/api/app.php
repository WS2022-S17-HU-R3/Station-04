<?php

class Router
{
    private $request;
    private $routes = [];
    private $db;


    public function __construct( $request )
    {
        $this->request = $request;
    }

    public function register( $method, $url, $callback )
    {
        array_push($this->routes, [
            "url" => $this->urlFactory( $url ),
            "method" => $method,
            "callback" => $callback
        ]);
    }

    public function dbConnect( $config )
    {
        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['db']};charset=utf8",
            $config["username"],
            $config["password"]
        );
    }

    public function run()
    {
        $method = false;

        foreach ( $this->routes as $array )
        {
            if ( $this->routeEquals( $array["url"], $this->request["url"] ) )
            {
                if ( $array["method"] !== $this->request["method"] )
                {
                    $method = true;
                    continue;
                }

                $response = call_user_func_array( $array["callback"], array($this->request, $this->db) );

                echo json_encode($response);
                exit();
            }
        }

        if($method)
        {
            header('HTTP/1.1 405 Method Not Allowed');
            exit();
        }

        header('HTTP/1.1 404 Not Found');
        exit();
    }


    private function urlFactory( $string )
    {
        $resp = [];

        $parsed = explode( '/', $string );
        foreach ( $parsed as $item )
        {
            if ( strlen( $item ) > 1 )
            {
                $resp[$item] = ( strpos($item, '{') !== false && strpos($item, '}') !== false ) ? 0 : 1;
            }
        }

        return $resp;
    }

    private function routeEquals( $haystack, $needle )
    {
        if ( count($haystack) !== count($needle) ) return false;

        $i = 0;
        foreach ( $haystack as $key => $value )
        {
            if ( $value == 1 && $key !== $needle[$i] ) return false;

            $i++;
        }

        return true;
    }
}