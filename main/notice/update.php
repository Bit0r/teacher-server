<?php
require_once '../../lib/teacher-funcs.php';

$notice = json_input();
$notice_id = intval($notice['notice_id']);

json_header();

try {
    $username = auth(ROLE_ADMIN);
    $db = connect_teacher();
    $db->beginTransaction();

    #检查是否为同一用户
    $query =
        "SELECT COUNT(*)
        FROM notice
        WHERE notice_id = $notice_id AND username = '$username'";
    if ($db->query($query)->fetchColumn() == 0) {
        throw new Exception('无权更改', 41);
    }

    #更新通知内容
    $query =
        "UPDATE notice 
        SET 
            content = ?
        WHERE
            notice_id = $notice_id";

    $stmt = $db->prepare($query);
    $stmt->execute([$notice['content']]);

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
