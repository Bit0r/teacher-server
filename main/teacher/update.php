<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    # 构造查询语句
    $query = 'UPDATE teacher SET';
    if ($_SESSION['role_type'] == ROLE_ADMIN) {
        $query .= empty($teacher['teacher_name']) ? '' : ' teacher_name = ?,';
        $query .= empty($teacher['title']) ? '' : ' title = ?,';
        $query .= empty($teacher['salary']) ? '' : ' salary = ?,';
        $query .= empty($teacher['gender']) ? '' : ' gender = ?,';
    }
    $query = mb_substr($query, 0, mb_strlen($query) - 1) . ' WHERE teacher_id = ?';

    $stmt = $db->prepare($query);

    # 构造查询参数
    if (!empty($teacher['teacher_name'])) {
        $params[] = $teacher['teacher_name'];
    }
    if (!empty($teacher['title'])) {
        $params[] = $teacher['title'];
    }
    if (!empty($teacher['salary'])) {
        $params[] = $teacher['salary'];
    }
    if (!empty($teacher['gender'])) {
        $params[] = $teacher['gender'];
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
