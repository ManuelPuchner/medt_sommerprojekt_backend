<?php

use db\User;

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
