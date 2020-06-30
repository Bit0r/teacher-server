<?php
require_once '../../lib/teacher-funcs.php';

$project_id = intval($_GET['project_id']);

json_header();

try {
    $db = connect_teacher();
    session_start();
    switch ($_SESSION['role_type']) {
        case ROLE_TEACHER:
            $query =
                "SELECT 
                    *
                FROM
                    project
                WHERE
                    project_id = $project_id";
            $result = $db->query($query)->fetch();
            if ($_SESSION['username'] !== $result['teacher_id']) {
                throw new Exception('没有访问权限', 1);
            }
            break;
        case ROLE_ADMIN:
            $query =
                "SELECT 
                    project.*, teacher_name
                FROM
                    project
                        JOIN
                    teacher USING (teacher_id)
                WHERE
                    project_id = $project_id";
            $result = $db->query($query)->fetch();
            break;
        default:
            throw new Exception('没有访问权限', 1);
            break;
    }

    $response = create_response();
    $response['message'] = $result;
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
