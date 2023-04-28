<?php

use db\Post;
use utils\HttpErrorCodes;
use utils\Response;

require_once '../../db/Post.php';
require_once '../../db/User.php';
require_once '../../db/Like.php';
require_once '../../utils/Response.php';
require_once '../../utils/HttpErrorCodes.php';

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = unserialize($_SESSION['user']);

$postId = $_POST['postId'];

if($postId == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post id is null")->send();
}

$dbPost = Post::getById($postId);

if($dbPost == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post not found")->send();
}

$isLiked = $dbPost->toggleLike($user->getId());

if ($isLiked) {
    Response::ok("Post liked successfully")->send();
} else {
    Response::ok("Post unliked successfully")->send();
}
