<?php

require_once './db/DB.php';
require_once './db/Post.php';
require_once './db/User.php';
require_once './db/Comment.php';
require_once './Response.php';
require_once './HttpErrorCodes.php';

$allPosts = Post::getAll();

var_dump($allPosts);