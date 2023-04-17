<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../db/Comment.php';
require_once '../../db/Post.php';
require_once '../../Response.php';
require_once '../../HttpErrorCodes.php';
require_once '../../db/Like.php';

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = $_SESSION['user'];

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
