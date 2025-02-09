<?php

require 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}


$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];


$table = preg_replace('/[^a-z0-9_]+/i', '', array_shift($request));
$key = array_shift($request);

function CheckInputs($input, $requiredFields)
{
    $errors = [];

    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            $errors[] = "$field is required";
        }
    }

    if (!empty($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!empty($input['phone']) && !preg_match('/^[0-9]{10,15}$/', $input['phone'])) {
        $errors[] = "Invalid phone number format";
    }

    if (!empty($input['password']) && strlen($input['password']) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    return $errors;
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }

        } else {
            $query = "SELECT * FROM users";
            $result = $mysqli->query($query);

            if ($result) {
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                echo json_encode($users);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to fetch users"]);
            }
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $requiredFields = ['first_name', 'second_name', 'last_name', 'email', 'phone', 'username', 'password'];

        $errors = CheckInputs($input, $requiredFields);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            exit();
        }

        $hashed_password = password_hash($input['password'], PASSWORD_BCRYPT);

        $query = "INSERT INTO users (first_name, second_name, last_name, email, phone, username, password,address, created_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?,?, NOW())";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param(
            "ssssssss",
            $input['first_name'],
            $input['second_name'],
            $input['last_name'],
            $input['email'],
            $input['phone'],
            $input['username'],
            $hashed_password,
            $input['address']

        );

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "User created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to create user"]);
        }
        break;

    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), associative: true);
        $requiredFields = ['first_name', 'second_name', 'last_name', 'email', 'phone'];

        $errors = CheckInputs($input, $requiredFields);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "UPDATE users SET first_name = ?, second_name = ?, last_name = ?,password=?, email = ?, phone = ?,address=? WHERE id = ?";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sssssssi", $input['first_name'], $input['second_name'], $input['last_name'], $input['password'], $input['email'], $input['phone'], $input['address'], $id);
            $stmt->execute();
        } elseif (!empty($input['email'])) {
            $query = "UPDATE users SET first_name = ?, second_name = ?, last_name = ?, phone = ?, username = ? WHERE email = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssssss", $input['first_name'], $input['second_name'], $input['last_name'], $input['phone'], $input['username'], $input['email']);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "User ID or email is required to update user"]);
            exit();
        }

        if ($stmt->execute()) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update user"]);
        }
        break;

    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($_GET['id'])) {

            $id = $_GET['id'];
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif (!empty($input['email'])) {
            $query = "DELETE FROM users WHERE email = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $input['email']);
        } elseif (!empty($input['first_name']) && !empty($input['second_name']) && !empty($input['last_name'])) {
            $query = "DELETE FROM users WHERE first_name=? AND second_name=? AND last_name=?";

            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sss", $input['first_name'], $input['second_name'], $input['last_name']);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "User ID or email is required to delete user"]);
            exit();
        }

        if ($stmt->execute()) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete user"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

$mysqli->close();

?>