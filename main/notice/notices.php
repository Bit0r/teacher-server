<?php
require_once '../../lib/teacher-funcs.php';

$page = json_input();

json_header();

try {
    auth(ROLE_ADMIN | ROLE_TEACHER);

    $db = connect_teacher();
    $query =
        'SELECT notice_id, username, stamp
        FROM notice
        ORDER BY notice_id DESC
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
