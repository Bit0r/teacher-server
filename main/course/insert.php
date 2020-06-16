<?php
require_once '../../lib/teacher-funcs.php';

$teacher_course = json_input();

json_header();

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    $markers = create_markers(5);
    $query = "INSERT INTO teacher_course(teacher_id,class_name,course_name,course_year,semester) VALUES $markers";

    $stmt = $db->prepare($query);
    $stmt->execute([
        $teacher_course['teacher_id'],
        $teacher_course['class_name'],
        $teacher_course['course_name'],
        $teacher_course['course_year'],
        $teacher_course['semester']
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
