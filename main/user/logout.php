<?php
require_once '../../lib/teacher-funcs.php';

json_header();

session_start();
session_destroy();

echo json_encode(create_response());
