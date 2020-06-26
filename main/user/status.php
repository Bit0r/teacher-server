<?php
require_once '../../lib/teacher-funcs.php';

json_header();

session_start();
$user['role_type'] = $_SESSION['role_type'];
$user['username'] = $_SESSION['username'];

$response = create_response();
$response['message'] = $user;

echo json_encode($response);
