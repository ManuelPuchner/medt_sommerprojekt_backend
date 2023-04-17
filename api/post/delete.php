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

$postId = $_GET['id'];

if($postId == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post id is null")->send();
}

$dbPost = Post::getById($postId);

if($dbPost == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post not found")->send();
}

if($dbPost->getUserId() != $user->getId()) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not the owner of this post")->send();
}

Post::delete($postId);

Response::ok("Post deleted successfully")->send();

