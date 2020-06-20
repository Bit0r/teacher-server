<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth(ROLE_ADMIN);
    $db = connect_teacher();

    # 检查新id合法性
    teacher_id_check($teacher['teacher_id']);

    # 构造查询语句和参数
    $query = 'UPDATE teacher SET';
    $teacher_id_old = $teacher['teacher_id_old'];
    unset($teacher['teacher_id_old']);
    foreach ($teacher as $key => $value) {
        $query .= " $key = ?,";
        $params[] = $value;
    }

    $query = mb_substr($query, 0, mb_strlen($query) - 1) . ' WHERE teacher_id = ?';
    $params[] = $teacher_id_old;

    #执行查询
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
