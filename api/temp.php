<?php

require_once '../db/DB.php';

require_once '../db/User.php';
require_once '../db/Comment.php';
require_once '../Response.php';
require_once '../HttpErrorCodes.php';

$db = DB::getInstance();


$user = User::getByEmail("m.puchner@students.htl-leonding.ac.at");

if($user == null) {
    $user = User::create("Manuel", "m.puchner@students.htl-leonding.ac.at", "test", UserType::STUDENT);
}


$allUsers = User::getAll();


header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'data' => array(
        'users' => $allUsers
    )
));





