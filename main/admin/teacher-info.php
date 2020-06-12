<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth_admin();

    $db = connect_teacher();

    $query = 'UPDATE teacher SET';
    $query .= empty($teacher['title']) ? '' : ' title = ?,';
    $query .= empty($teacher['salary']) ? '' : ' salary = ?,';
    $query = mb_substr($query, 0, mb_strlen($query) - 1) . ' WHERE teacher_id = ?';

    $stmt = $db->prepare($query);

    if (!empty($teacher['title'])) {
        $params[] = $teacher['title'];
    }
    if (!empty($teacher['salary'])) {
        $params[] = $teacher['salary'];
    }
    $params[] = $teacher['teacher_id'];
    $stmt->execute($params);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
