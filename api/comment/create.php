<?php
require_once '../../db/DB.php';
require_once '../../db/User.php';
require_once '../../db/Comment.php';
require_once '../../db/Post.php';
require_once '../../Response.php';
require_once '../../HttpErrorCodes.php';


session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = $_SESSION['user'];

$comment = $_POST['comment'];

$postId = $_POST['postId'];

if($comment == null || $postId == null) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "Comment or post id is null")->send();
}

$commentObj = Comment::create($comment, $user->getId(), $postId);

Response::created("Comment created successfully", $commentObj)->send();
