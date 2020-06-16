<?php
require_once '../../lib/teacher-funcs.php';

$teacher_course = json_input();

json_header();

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    $id = $teacher_course['id'];

    # 构造查询语句和参数
    $query = 'UPDATE teacher_course SET';

    unset($teacher_course['id']);
    foreach ($teacher_course as $key => $value) {
        $query .= " $key = ?,";
        $params[] = $value;
    }

    $query = mb_substr($query, 0, mb_strlen($query) - 1) . ' WHERE id = ?';
    $params[] = $id;

    # 执行查询
    $stmt = $db->prepare($query);
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
