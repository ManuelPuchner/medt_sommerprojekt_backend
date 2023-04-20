<?php

require_once './db/DB.php';
require_once './db/Post.php';
require_once './db/User.php';
require_once './db/Comment.php';

$password = "test1234";

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash;
echo "\n";

echo password_hash($password, PASSWORD_DEFAULT);
echo "\n";

$verify = password_verify($password, $hash);

echo $verify ? "true" : "false";
