<?php
require_once '../../lib/teacher-funcs.php';

$teacher = json_input();

json_header();

try {
    auth(ROLE_ADMIN);

    # 检查新id合法性
    $teacher_id = $teacher['teacher_id'];
    if (!empty($teacher_id)) {
        teacher_id_check($teacher_id);
    }

    $db = connect_teacher();
    $db->beginTransaction();

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


    # 修改登陆用户名和密码
    if (!empty($teacher_id)) {
        $query =
            'UPDATE users
            SET
                username = ?,
                password_hash = ?
            WHERE username = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([
            $teacher_id,
            password_hash($teacher_id, PASSWORD_DEFAULT),
            $teacher_id_old
        ]);
    }

    $db->commit();
    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
