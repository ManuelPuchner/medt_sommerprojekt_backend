<?php

require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../db/Post.php';
require_once '../../utils/Response.php';
require_once '../../utils/HttpErrorCodes.php';

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = $_SESSION['user'];

$likedPosts = $user->getLikedPosts();

Response::ok("Liked posts fetched successfully", $likedPosts)->send();
