<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';

session_start();

$email = $_POST['email'];
$password = $_POST['password'];


$dbUser = User::getByEmail($email);


header('Content-Type: application/json');

echo json_encode(array(
    'success' => true,
    'data' => array(
        'email' => $email,
        'password' => $password,
        'dbUser' => $dbUser->expose()
    )
));


//if($dbUser == null) {
//    header("Location: login.php?error=1");
//    exit();
//}
//
//if(!password_verify($password, $dbUser->getPassword())) {
//    header("Location: login.php?error=2");
//    exit();
//}
//
//$_SESSION['user'] = $dbUser;
//
//header("Location: temp.php");
//exit();
