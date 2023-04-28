<?php

use db\User;
use utils\HttpErrorCodes;
use utils\Response;

session_start();

if(!isset($_SESSION['user'])) {
    Response::error(HttpErrorCodes::HTTP_UNAUTHORIZED, "You are not logged in")->send();
}

$user = unserialize($_SESSION['user']);

$getBy = $_GET['by'];

if($getBy == "id") {
    $id = $_GET['id'];

    if($id == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "Id is null")->send();
    }
    $user = User::getById($id);
    if($user == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "User not found")->send();
    }
} else if ($getBy == "name") {
    $name = $_GET['name'];
    if($name == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "Username is null")->send();
    }
    $user = User::getByName($name);
    if($user == null) {
        Response::error(HttpErrorCodes::HTTP_NOT_FOUND, "User not found")->send();
    }
} else if ($getBy == 'session') {
    $user = $_SESSION['user'];
} else {
    Response::error(HttpErrorCodes::HTTP_NOT_IMPLEMENTED, "Not implemented")->send();
}


if(isset($_GET['include'])) {
    $include = explode(",", $_GET['include']);

    if (in_array("postCount", $include)) {
        $user->getPostCount();
    }

    if(in_array("posts", $include)) {
        echo json_encode($user);
        $user->getPosts();
    }
}

Response::ok("User fetched successfully", $user)->send();