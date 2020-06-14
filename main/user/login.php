<?php
require_once '../../lib/teacher-funcs.php';

$user = json_input();

json_header();

try {
    $db = connect_teacher();

    $query =
        'SELECT password_hash, role_type
        FROM users
        WHERE username = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$user['username']]);
    $result = $stmt->fetch();

    # 验证账号密码
    if ($stmt->rowCount() == 0) {
        throw new Exception("账号错误", 1);
    } elseif (!password_verify($user['password'], $result['password_hash'])) {
        throw new Exception("密码错误", 2);
    }

    # 将登陆信息写入会话同时响应身份
    $response = create_response();
    session_start();
    $_SESSION['role_type'] = $response['message'] = $result['role_type'];
    $_SESSION['username'] = $user['username'];
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
