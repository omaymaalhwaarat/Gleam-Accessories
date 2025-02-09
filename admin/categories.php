<?php

require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'));
$uri = $_SERVER['REQUEST_URI'];
$action = basename(parse_url($uri, PHP_URL_PATH));


if ($_SERVER['REQUEST_METHOD'] == "POST" && $action == 'add') {

    $name = $data->name ?? "";


    if (empty($name)) {
        echo json_encode(['error' => 'All field are required.']);
        http_response_code(400);
        exit;
    }

    try {

        $statment = $mysqli->prepare('INSERT INTO categories (name) VALUES(?)');
        $statment->bind_param('s', $name);

        $statment->execute();

        echo json_encode([
            'message' => 'The category has been created sucessfully.',

            'name' => $name
        ]);
        http_response_code(201);

    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to create teacher: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

if (
    $_SERVER['REQUEST_METHOD'] === "GET" && strpos(
        $_SERVER['REQUEST_URI'],
        '/get'
    ) !== false
) {

    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    try {

        if ($id) {
            $statment = $mysqli->prepare("SELECT * FROM categories WHERE id = ?");
            $statment->bind_param("i", $id);
            $statment->execute();
            $result = $statment->get_result();
            $category = $result->fetch_assoc();

            if ($category) {
                echo json_encode($category);
            } else {
                echo json_encode(['error' => 'The category is NOT found']);
                http_response_code(404);
            }
        } else {
            $result = $mysqli->query("SELECT * FROM categories ORDER BY id ASC");
            $categories = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($categories);
        }

    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to retrieve categories: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $action == 'update') {
    $id = $data->id ?? 0;
    $name = $data->name ?? "";


    if (empty($name)) {
        echo json_encode(['error' => 'The  name field is required.']);
        http_response_code(400);
        exit;
    }

    try {

        $statment = $mysqli->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $statment->bind_param('si', $name, $id);
        $statment->execute();

        if ($statment->affected_rows > 0) {
            echo json_encode(['message' => 'Category updated successfully.']);
        } else {
            echo json_encode(['error' => 'The category is NOT found.']);
            http_response_code(404);
        }

    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to update category: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "DELETE" && $action = 'delete') {
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    if (empty($id)) {
        echo json_encode(['error' => 'The category ID is required.']);
        http_response_code(400);
        exit;
    }
    try {
        $stmt = $mysqli->prepare('DELETE FROM categories WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['message' => 'The category deleted successfully.']);
        } else {
            echo json_encode(['error' => 'The category is NOT found.']);
            http_response_code(404);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        http_response_code(500);
    }

}


?>