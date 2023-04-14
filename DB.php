<?php

class DB {

    private static $conn = null;

    public static function getConnection() {
        $host = "127.0.0.1";
        $user = "web";
        $pw = "web";
        $db = "web_";
        $port = 3306;
        if (self::$conn == null) {
            self::$conn = new mysqli($host, $user, $pw, $db, $port);
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    private function __construct() {
        self::getConnection();
    }

}