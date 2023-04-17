<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../Response.php';
require_once '../../HttpErrorCodes.php';

session_start();

$email = $_POST['email'];
$password = $_POST['password'];


$dbUser = User::getByEmail($email);

if($dbUser == null) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "User not found")->send();
}

if(!password_verify($password, $dbUser->getPassword())) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "Wrong password")->send();
}

$_SESSION['user'] = $dbUser;

Response::ok("Login successful",$dbUser)->send();
