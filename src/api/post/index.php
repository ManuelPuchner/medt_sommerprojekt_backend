<?php
session_start();

use db\Post;
use utils\HttpErrorCodes;
use utils\Response;

require_once '../../db/User.php';
require_once '../../db/Post.php';
require_once '../../db/Like.php';
require_once '../../db/Comment.php';
require_once '../../utils/Response.php';



if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$page = $_GET['page'];
$length = $_GET['length'];

if(!isset($page) || !isset($length)) {
    Response::error(HttpErrorCodes::HTTP_INTERNAL_SERVER_ERROR, "Page or length is null")->send();
}

if(isset($_GET['include'])) {
    $include = explode(",", $_GET['include']);
    $allPosts = Post::getAllPaginated($page, $length, $include);
} else {
    $allPosts = Post::getAllPaginated($page, $length);
}


Response::ok("Posts fetched successfully", $allPosts)->send();