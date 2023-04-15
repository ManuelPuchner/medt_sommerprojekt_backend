<?php

require_once '../db/DB.php';

require_once '../db/User.php';
require_once '../db/Comment.php';

$db = DB::getInstance();


$user = User::getByEmail("m.puchner@students.htl-leonding.ac.at");

if($user == null) {
    $user = User::create("Manuel", "m.puchner@students.htl-leonding.ac.at", "test", UserType::STUDENT);
}

var_dump($user);





