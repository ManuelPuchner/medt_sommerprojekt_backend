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

$getBy = $_GET['by'];

if($getBy == "id") {
    $id = $_GET['id'];

    echo $id;
    if($id == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "Id is null")->send();
    }
    $post = Post::getById($id);
    if($post == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Post not found")->send();
    }
    Response::ok("Post fetched successfully", $post)->send();
} else if ($getBy == "user") {
    $userId = $_GET['id'];
    if($userId == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "User id is null")->send();
    }
    $posts = Post::getByUserId($userId);
    if($posts == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "Posts not found")->send();
    }
    Response::ok("Posts fetched successfully", $posts)->send();
} else {
    Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "Not implemented")->send();
}
