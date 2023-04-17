<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../Response.php';
require_once '../../HttpErrorCodes.php';

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

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$userType = 'STUDENT';

$user = User::create($name, $email, $passwordHash, $userType);

$_SESSION['user'] = $user;

Response::ok("Registration successful",$user->expose()) -> send();



