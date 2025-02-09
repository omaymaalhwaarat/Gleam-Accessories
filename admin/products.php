<?php
require 'db.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


//Handling JSON file
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$uri = $_SERVER['REQUEST_URI'];
$action = basename(parse_url($uri, PHP_URL_PATH)); //basename to get exavtly the last part of the uri


if ($_SERVER['REQUEST_METHOD'] == "POST" && $action == 'add') {

    // Get input data
    $name = $data['name'] ?? "";
    $disc = $data['description'] ?? "";
    $price_cost = $data['price_cost'] ?? 0;
    $price_with_Revenue = $data['price_with_Revenue'] ?? 0;
    $quantity = $data['quantity'] ?? 0;
    $category_id = $data['category_id'] ?? 0;
    $image = $data['image'] ?? ""; // Directly take the image URL from the request

    // Validate required fields
    if (empty($name) || empty($disc) || empty($price_cost) || empty($price_with_Revenue) || empty($quantity) || empty($category_id) || empty($image)) {
        echo json_encode(['error' => 'All fields are required, including image URL.']);
        http_response_code(400);
        exit;
    }

    // Validate the image URL
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        echo json_encode(['error' => 'Invalid image URL.']);
        http_response_code(400);
        exit;
    }

    try {
        // Prepare and execute the SQL insert statement
        $stmt = $mysqli->prepare('INSERT INTO products (name, description, price_cost, price_with_Revenue, quantity, image, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssddisi', $name, $disc, $price_cost, $price_with_Revenue, $quantity, $image, $category_id);

        // Execute and check the result
        if ($stmt->execute()) {
            echo json_encode([
                'message' => 'Product created successfully.',
                'product' => [
                    'name' => $name,
                    'description' => $disc,
                    'price_cost' => $price_cost,
                    'price_with_Revenue' => $price_with_Revenue,
                    'quantity' => $quantity,
                    'image' => $image,
                    'category_id' => $category_id
                ]
            ]);
            http_response_code(201);
        } else {
            echo json_encode(['error' => 'Failed to create product.']);
            http_response_code(500);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

//Get products
if ($_SERVER['REQUEST_METHOD'] === "GET" && strpos($_SERVER['REQUEST_URI'], '/get') !== false) {


    $id = isset($_GET['id']) ? $_GET['id'] : "";


    try {
        if ($id) {
            // Fetch product by id
            $stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $id); // Bind the 'id' as an integer
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                echo json_encode($product);
            } else {
                echo json_encode(['error' => 'Product not found.']);
                http_response_code(404);
            }
        } else {
            // Fetch all products
            $result = $mysqli->query("SELECT * FROM products");
            $products = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($products);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to retrieve products: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $action == 'update') {
    $id = $data['id'] ?? 0;
    $name = $data['name'] ?? "";
    $price_cost = $data['price_cost'] ?? 0;
    $price_with_Revenue = $data['price_with_Revenue'] ?? 0;
    $quantity = $data['quantity'] ?? 0;
    $image = $data['image'] ?? null;
    $category_id = $data['category_id'] ?? 0;

    if (empty($id) || empty($name) || empty($price_cost) || empty($price_with_Revenue) || empty($quantity) || empty($category_id)) {
        echo json_encode(['error' => 'All fields are required including ID.']);
        http_response_code(400);
        exit;
    }

    try {
        $stmt = $mysqli->prepare('UPDATE products SET name=?, price_cost=?, price_with_Revenue=?, quantity=?, image=?, category_id=? WHERE id=?');
        $stmt->bind_param('sddisii', $name, $price_cost, $price_with_Revenue, $quantity, $image, $category_id, $id); // Correct binding order and types
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['message' => 'Product updated successfully.']);

        } else {
            echo json_encode(['error' => 'Product not found or no changes made.']);
            http_response_code(404);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to update Product: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "DELETE" && $action = 'delete') {
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    if (empty($id)) {
        echo json_encode(['error' => 'Product ID is required.']);
        http_response_code(400);
        exit;
    }
    try {
        $stmt = $mysqli->prepare('DELETE FROM products WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['message' => 'product deleted successfully.']);
        } else {
            echo json_encode(['error' => 'product not found.']);
            http_response_code(404);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        http_response_code(500);
    }

}




















?>