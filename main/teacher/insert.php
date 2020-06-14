<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    $markers = create_markers(5);
    $query = "INSERT INTO teacher(teacher_id,teacher_name,gender,title,salary) VALUES $markers";

    #执行查询
    $stmt = $db->prepare($query);
    $stmt->execute([
        $teacher['teacher_id'],
        $teacher['teacher_name'],
        $teacher['gender'],
        $teacher['title'],
        $teacher['salary']
    ]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
