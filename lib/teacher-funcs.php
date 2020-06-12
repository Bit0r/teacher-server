<?php
require_once '../../lib/common.php';

function connect_teacher()
{
    return pdo_connect('teacher', 'teacher', '%3e8Um0_');
}


function auth_teacher()
{
    session_start();
    $teacher_id = $_SESSION['teacher_id'];
    if (empty($teacher_id)) {
        throw new Exception("请先登录", 1);
    }
    return $teacher_id;
}

function auth_admin()
{
    session_start();
    $admin_id = $_SESSION['admin_id'];
    if (empty($admin_id)) {
        throw new Exception("非管理员", 1);
    }
    return $admin_id;
}
