<?php
require_once '../../lib/teacher-funcs.php';

$approval = json_input();

json_header();

try {
    $username = auth(ROLE_ADMIN);
    $db = connect_teacher();

    $query =
        'UPDATE project
        SET
            approve = ?,
            approver = ?,
            approve_date = NOW()
        WHERE
            project_id = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([
        $approval['approve'] == 'true',
        $username,
        $approval['project_id']
    ]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
