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

$postId = $_GET['postId'];

if($postId == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post id is null")->send();
}

$post = Post::getById($postId);

if(isset($_GET['count']) && $_GET['count'] == "true") {
    $count = $post->getLikeCount();
    Response::ok("Likes count fetched successfully", $count)->send();
}

if($post == null) {
    Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post not found")->send();
}

$likes = $post->getLikes();

Response::ok("Likes fetched successfully", $likes)->send();