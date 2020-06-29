<?php
require_once '../../lib/teacher-funcs.php';

json_header();

$teacher_id = $_GET['teacher_id'];

try {
    auth(ROLE_ADMIN);

    $db = connect_teacher();

    #插入教师信息
    $query =
        'UPDATE teacher
        SET
            departure_time = NOW()
        WHERE
            teacher_id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$teacher_id]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
