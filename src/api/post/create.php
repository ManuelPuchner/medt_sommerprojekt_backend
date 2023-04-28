<?php
session_start();

use db\Post;
use db\User;
use utils\HttpErrorCodes;
use utils\Response;

require_once '../../db/User.php';
require_once '../../db/Post.php';
require_once '../../utils/Response.php';
require_once '../../utils/HttpErrorCodes.php';

if(!isset($_SESSION['user'])) {
     Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = unserialize($_SESSION['user']);


$image = $_POST['image'];
$description = $_POST['description'];
$date = new DateTime();
$userId = $user->getId();

if($image == null || $description == null) {
    Response::error(HttpErrorCodes::HTTP_INTERNAL_SERVER_ERROR, "Image or description is null")->send();

}

$postObj = Post::create($image, $description, $date, $userId);

Response::created("Post created successfully", $postObj)->send();