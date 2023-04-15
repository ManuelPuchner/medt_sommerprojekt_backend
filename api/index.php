<?php

require_once '../db/DB.php';

require_once '../db/User.php';
require_once '../db/Comment.php';

$user = User::getByEmail("m.puchner@students.htl-leonding.ac.at");

if($user == null) {
    $user = User::create("Manuel", "m.puchner@students.htl-leonding.ac.at", "test", 'STUDENT');
}

Header('Content-Type: application/json');

echo json_encode(array(
    'success' => true,
    'data' => array(
        'user' => $user->expose()
    )
));
