<?php
require_once '../../lib/common.php';

function connect_teacher()
{
    return pdo_connect('teacher', 'teacher', '%3e8Um0_');
}

# 定义关于角色的常量
const ROLE_TEACHER = 0b1;
const ROLE_ADMIN = 0b10;

function auth(int $role_type)
{
    session_start();
    # 在C中，& | ^的优先级仅比&& ||高
    if (0 == ($_SESSION['role_type'] & $role_type)) {
        throw new Exception('无权访问本资源', 1);
    }
    return $_SESSION['username'];
}

function teacher_id_check(string $teacher_id)
{
    if (preg_match('/^[12]\d{8}[1-7][0-2]\d{6}$/', $teacher_id) !== 1) {
        throw new Exception('教师ID不合法', 12);
    }
}
