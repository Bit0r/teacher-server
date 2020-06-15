<?php
require_once '../../lib/teacher-funcs.php';

json_header();

try {
    $db = connect_teacher();

    $query =
        'SELECT teacher_id
        FROM teacher';
    $stmt_teacher = $db->query($query);

    $query = "INSERT users VALUES (?, ?, 1)";
    $stmt_users = $db->prepare($query);

    while ($teacher_id = $stmt_teacher->fetchColumn()) {
        $stmt_users->execute([
            $teacher_id,
            password_hash(mb_substr($teacher_id, -6), PASSWORD_DEFAULT)
        ]);
    }

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
