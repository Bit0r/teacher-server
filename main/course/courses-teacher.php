<?php
require_once '../../lib/teacher-funcs.php';

$page = json_input();

json_header();

try {
    $username = auth(ROLE_TEACHER);

    $db = connect_teacher();
    $query =
        "SELECT
            course_name,
            class_name,
            course_year,
            semester
        FROM
            teacher_course
        WHERE
            teacher_id = '$username'
        ORDER BY course_year DESC , semester
        LIMIT ? , ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$page['offset'], $page['row_count']]);

    $response = create_response();
    $response['message'] = $stmt->fetchAll();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
