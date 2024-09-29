<?php
// 引入数据库配置
require 'db_config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // 错误处理模式
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
        PDO::ATTR_EMULATE_PREPARES => false 
    ]);


    $query_count = $pdo->query("SELECT COUNT(*) FROM wa");
    $row_count = $query_count->fetchColumn();

    if ($row_count > 0) {

        $random_offset = rand(0, $row_count - 1);


        $stmt = $pdo->prepare("SELECT content FROM wa LIMIT 1 OFFSET :offset");
        $stmt->bindParam(':offset', $random_offset, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row) {
            // 200
            echo json_encode([
                'code' => 200,
                'status' => 'success',
                'message' => '随机一言API - Ver1.4',
                'data' => $row['content']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // 400
            echo json_encode([
                'code' => 400,
                'status' => 'error',
                'message' => '随机一言API - Ver1.4',
                'data' => 'No data found'
            ], JSON_UNESCAPED_UNICODE);
        }
    } else {
        // 401
        echo json_encode([
            'code' => 401,
            'status' => 'error',
            'message' => '随机一言API - Ver1.4',
            'data' => 'No rows in the database'
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    // 402
    echo json_encode([
        'code' => 402,
        'status' => 'error',
        'message' => '随机一言API - Ver1.4',
        'data' => 'Database connection failed: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
