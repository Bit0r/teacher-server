<?php
require_once '../../lib/teacher-funcs.php';

$page = json_input();

json_header();

try {
    auth(ROLE_ADMIN);

    $db = connect_teacher();
    $query =
        'SELECT *
        FROM teacher
        WHERE departure_time IS NULL';
    $params = [];

    $keyword = $page['keyword'];
    if (!empty($keyword)) {
        if (teacher_id_format($keyword)) {
            $query .= ' AND teacher_id = ?';
            $params[] = $keyword;
        } else {
            $query .= ' AND teacher_name LIKE ?';
            $params[] = "%$keyword%";
        }
    }

    $query .= ' LIMIT ? , ?';
    $params[] = $page['offset'];
    $params[] = $page['row_count'];

    $stmt = $db->prepare($query);
    $stmt->execute($params);

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
