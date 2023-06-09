<?php

use db\User;
use utils\HttpErrorCodes;
use utils\Response;

require_once '../../db/User.php';
require_once '../../utils/Response.php';
require_once '../../utils/HttpErrorCodes.php';

session_start();

function validateEmail($email) : bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password): bool
{
    return strlen($password) >= 8;
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!validateEmail($email)) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "Invalid email")->send();
}

$dbUser = User::getByEmail($email);

if($dbUser != null) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "User already exists")->send();
}

if (!validatePassword($password)) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "Password too short")->send();
}

$userType = 'STUDENT';

$user = User::create($name, $email, $password, $userType);

$_SESSION['user'] = serialize($user);

Response::ok("Registration successful",$user) -> send();
