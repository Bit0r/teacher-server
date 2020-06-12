<?php
require_once '../../lib/common.php';

function connect_teacher()
{
    return pdo_connect('teacher', 'teacher', '%3e8Um0_');
}


function auth_teacher()
{
    session_start();
    if ($_SESSION['role_type'] != 1) {
        throw new Exception('您不是教师', 11);
    }
}

function auth_admin()
{
    session_start();
    if ($_SESSION['role_type'] != 2) {
        throw new Exception('您不是管理员', 12);
    }
}

function auth_login()
{
    session_start();
    $username = $_SESSION['username'];
    if (empty($username)) {
        throw new Exception('您尚未登陆', 10);
    }
    return $username;
}
