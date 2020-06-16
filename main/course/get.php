<?php
require_once '../../lib/teacher-funcs.php';

json_header();

try {
    auth(ROLE_ADMIN);

    $db = connect_teacher();

    $query =
        'SELECT *
        FROM teacher_course
        WHERE id = ' . intval($_GET['id']);

    $response = create_response();
    $response['message'] = $db->query($query)->fetch();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
