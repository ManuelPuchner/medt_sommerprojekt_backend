<?php

require_once 'DB.php';

require_once 'User.php';
require_once 'Comment.php';

$db = DB::getInstance();


$user = User::getByEmail("m.puchner@students.htl-leonding.ac.at");

if($user == null) {
    $user = User::create("Manuel", "m.puchner@students.htl-leonding.ac.at", "test", 'STUDENT');
}

var_dump($user);





