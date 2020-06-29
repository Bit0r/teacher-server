<?php
require_once '../../lib/teacher-funcs.php';

json_header();

try {
    session_start();
    switch ($_SESSION['role_type']) {
        case ROLE_ADMIN:
            $where = '';
            break;
        case ROLE_TEACHER:
            $where = ' WHERE teacher_id = \'' . $_SESSION['username'] . '\'';
            break;
        default:
            throw new Exception('无权访问本资源', 1);
            break;
    }

    $db = connect_teacher();

    $query =
        "SELECT COUNT(*)
        FROM teacher_course
        $where";

    $response = create_response();
    $response['message'] = $db->query($query)->fetchColumn();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
