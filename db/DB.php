<?php
class DB {

    private static mysqli|null $conn = null;

    public static function getConnection() {
        $env = parse_ini_file(__DIR__ . '/../.env');

        $host = $env['DB_HOST'];
        $user = $env['DB_USER'];
        $pw = $env['DB_PW'];
        $db = $env['DB_NAME'];
        $port = $env['DB_PORT'];
        if (self::$conn == null) {
            self::$conn = new mysqli($host, $user, $pw, $db, $port);
            if (self::$conn->connect_error) {
                die("Connection failed: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }

    private static ?DB $instance = null;

    public static function getInstance(): ?DB
    {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    private function __construct() {
        self::getConnection();
    }

}