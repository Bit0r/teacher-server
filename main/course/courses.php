<?php
require_once '../../lib/teacher-funcs.php';

$page = json_input();

json_header();

try {
    auth(ROLE_ADMIN);

    $db = connect_teacher();
    $query =
        'SELECT
            id,
            course_name,
            class_name,
            teacher_id,
            teacher_name,
            course_year,
            semester
        FROM
            teacher_course
                JOIN
            teacher USING (teacher_id)
        ORDER BY course_year DESC , semester
        LIMIT ? , ?';
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
