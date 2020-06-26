<?php
require_once '../../lib/teacher-funcs.php';
json_header();

try {
    $username = auth(ROLE_ADMIN);

    #处理上传错误
    if ($_FILES['teachers']['error']) {
        switch ($_FILES['teachers']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $message = '文件过大';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = '文件超过表单容量';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = '文件上传中断';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = '没有接收到文件';
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = '危险文件';
                break;
        }
        throw new Exception($message, $_FILES['image']['error'] + 50);
    }

    #校验文件类型
    if ($_FILES['teachers']['type'] != 'text/csv') {
        throw new Exception('只能上传CSV文件', 62);
    }

    #防止恶意文件
    if (!is_uploaded_file($_FILES['teachers']['tmp_name'])) {
        throw new Exception("非法上传文件", 61);
    }

    # 备份上传的文件，用来审计
    $destination = '/var/uploaded/' . $username . '-' . time() . '.csv';
    if (!move_uploaded_file($_FILES['teachers']['tmp_name'], $destination)) {
        throw new Exception('文件移动失败，请重命名上传文件', 63);
    }

    #载入数据
    $query = <<<EOF
        LOAD DATA INFILE '$destination'
        IGNORE INTO TABLE teacher
        FIELDS TERMINATED BY ','
        OPTIONALLY ENCLOSED BY '"'
        LINES TERMINATED BY '\n'
    EOF;
    $db = connect_teacher();
    $db->exec($query);

    $response = create_response();
} catch (Exception $th) {
    $response = create_response($th);
} catch (PDOException $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
