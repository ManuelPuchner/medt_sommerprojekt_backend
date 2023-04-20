<?php

require_once '../db/DB.php';

require_once '../db/User.php';
require_once '../db/Comment.php';
require_once '../utils/Response.php';
require_once '../utils/HttpErrorCodes.php';

$user = User::getByEmail("m.puchner@students.htl-leonding.ac.at");

if($user == null) {
    $user = User::create("Manuel", "m.puchner@students.htl-leonding.ac.at", "test", 'STUDENT');
}

$allUsers = User::getAll();


header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'data' => array(
        'users' => $allUsers
    )
));
