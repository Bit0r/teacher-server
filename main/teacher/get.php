<?php
require_once '../../lib/teacher-funcs.php';

$teacher_id = $_GET['teacher_id'];

json_header();

try {
    if (empty($teacher_id)) {
        $teacher_id = auth(ROLE_TEACHER);
    } else {
        auth(ROLE_ADMIN);
    }

    $db = connect_teacher();

    $query =
        'SELECT *
        FROM teacher
        WHERE teacher_id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$teacher_id]);

    $response = create_response();
    $response['message'] = $stmt->fetch();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
