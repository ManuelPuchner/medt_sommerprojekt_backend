<?php

use utils\HttpErrorCodes;
use utils\Response;

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = unserialize($_SESSION['user']);

$likedPosts = $user->getLikedPosts();

Response::ok("Liked posts fetched successfully", $likedPosts)->send();
