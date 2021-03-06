<?php
require_once '../../lib/teacher-funcs.php';

$user = json_input();

json_header();

try {
    $username = auth(ROLE_ADMIN | ROLE_TEACHER);

    $db = connect_teacher();

    $new_password = $user['password'];
    if ($new_password != $user['verify']) {
        throw new Exception('两次输入的密码不一致', 5);
    }

    $query =
        'UPDATE users
        SET password_hash = ?
        WHERE username = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $username]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
