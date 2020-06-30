<?php
require_once '../../lib/teacher-funcs.php';

$application = json_input();

json_header();

try {
    $teacher_id = auth(ROLE_TEACHER);

    $db = connect_teacher();

    #插入教师信息
    $markers = create_markers(5);
    $query =
        "INSERT INTO project(project_name, project_content, teacher_id, start_date, end_date)
        VALUES $markers";

    $stmt = $db->prepare($query);
    $stmt->execute([
        $application['project_name'],
        $application['project_content'],
        $teacher_id,
        $application['start_date'],
        $application['end_date']
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
