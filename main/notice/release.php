<?php
require_once '../../lib/teacher-funcs.php';

$content = file_get_contents('php://input');

json_header();

try {
    $username = auth(ROLE_ADMIN);
    $db = connect_teacher();

    $query = "INSERT INTO notice(username, content) VALUES ('$username', ?)";

    $stmt = $db->prepare($query);
    $stmt->execute([$content]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
