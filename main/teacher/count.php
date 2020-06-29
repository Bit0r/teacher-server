<?php
require_once '../../lib/teacher-funcs.php';

json_header();

$keyword = $_GET['keyword'];

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    # 构造查询语句
    $query =
        'SELECT COUNT(*)
        FROM teacher
        WHERE departure_time IS NULL';
    $params = [];

    if (!empty($keyword)) {
        if (teacher_id_format($keyword)) {
            $query .= ' AND teacher_id = ?';
            $params[] = $keyword;
        } else {
            $query .= ' AND teacher_name LIKE ?';
            $params[] = "%$keyword%";
        }
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);

    $response = create_response();
    $response['message'] = $stmt->fetchColumn();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
