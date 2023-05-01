<?php
header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'data' => array(
        'message' => 'Welcome to the API of the social media app'
    )
));