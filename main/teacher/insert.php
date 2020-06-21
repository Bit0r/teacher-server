<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth(ROLE_ADMIN);

    teacher_id_check($teacher['teacher_id']);

    $db = connect_teacher();
    $db->beginTransaction();

    #插入教师信息
    $markers = create_markers(5);
    $query = "INSERT INTO teacher(teacher_id,teacher_name,gender,title,salary) VALUES $markers";

    $stmt = $db->prepare($query);
    $stmt->execute([
        $teacher['teacher_id'],
        $teacher['teacher_name'],
        $teacher['gender'],
        $teacher['title'],
        $teacher['salary']
    ]);

    #添加用户
    $query =
        'INSERT INTO users(username, password_hash, role_type) VALUES
        (?, ?, 1)';

    $stmt = $db->prepare($query);
    $stmt->execute([
        $teacher['teacher_id'],
        password_hash(mb_substr($teacher['teacher_id'], -6), PASSWORD_DEFAULT)
    ]);

    $db->commit();
    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
