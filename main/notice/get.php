<?php
require_once '../../lib/teacher-funcs.php';

json_header();

try {
    auth(ROLE_ADMIN | ROLE_TEACHER);

    $db = connect_teacher();

    $query =
        'SELECT *
        FROM notice
        WHERE notice_id = ' . intval($_GET['notice_id']);

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
