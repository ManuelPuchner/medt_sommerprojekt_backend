<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../db/Comment.php';
require_once '../../db/Post.php';
require_once '../../utils/Response.php';
require_once '../../utils/HttpErrorCodes.php';

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = $_SESSION['user'];


$image = $_POST['image'];
$description = $_POST['description'];
$date = new DateTime();
$userId = $user->getId();

if($image == null || $description == null) {
    Response::error(HttpErrorCodes::HTTP_INTERNAL_SERVER_ERROR, "Image or description is null")->send();
}

$postObj = Post::create($image, $description, $date, $userId);

Response::created("Post created successfully", $postObj)->send();