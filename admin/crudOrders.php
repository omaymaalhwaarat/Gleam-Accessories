<?PHP
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

$table = preg_replace('/[^a-z0-9_]+/i', '', array_shift($request));
$key = array_shift($request);

switch ($method) {

    case 'GET':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if (empty($_GET['CustomerName'])) {
            $query = "SELECT orders.id, date, status, CONCAT(users.first_name, ' ', users.second_name, ' ', users.last_name) AS CustomerName, users.email, users.username, amount, amount_after_discount
             FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY date DESC;";

            $result = $mysqli->query($query);

            if ($result) {
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }

                echo json_encode($orders);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to fetch orders."]);
            }
        } else {

            $customerName = $_GET['CustomerName'];

            $query = "WITH OrderDetails AS (
                SELECT o.date,
                p.name AS Item,
                o.status, 
                CONCAT(u.first_name, ' ', u.second_name, ' ', u.last_name) AS CustomerName,
                u.email,
                o.amount,
                p.discount,
                o.amount_after_discount    
                FROM orders o 
                INNER JOIN users u ON o.user_id = u.id
                INNER JOIN order_products op ON op.order_id = o.id
                INNER JOIN products p ON op.product_id = p.id
            ) 
            SELECT * FROM OrderDetails WHERE CustomerName =?;";

            $statement = $mysqli->prepare($query);
            $statement->bind_param("s", $customerName);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }

                echo json_encode($orders);

            } else {
                http_response_code(404);
                echo json_encode(["error" => "order not found"]);
            }
        }
        break;

    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? intval($input['id']) : 0;  // Get the ID from the body, not URL

        if (!empty($id)) {
            $allowed_statuses = ['Pending', 'Completed', 'Cancelled'];

            if (!in_array($input['status'], $allowed_statuses)) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid status value. Allowed: " . implode(", ", $allowed_statuses)]);
                exit();
            }

            $query = "UPDATE orders
                          SET status = ?, 
                              amount = ?, 
                              amount_after_discount = ? 
                          WHERE id = ?";

            $statement = $mysqli->prepare($query);
            $statement->bind_param(
                "sddi",
                $input['status'],
                $input['amount'],
                $input['amount_after_discount'],
                $id // Use $id from the body here
            );

            if ($statement->execute()) {
                echo json_encode(["message" => "Order updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Failed to update order"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Order ID is required"]);
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Retrieve 'id' from query string

        if (!empty($id)) {
            $query = "DELETE FROM orders WHERE id = ?";
            $statement = $mysqli->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                echo json_encode(["message" => "User deleted successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User not found or already deleted"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "User ID is required to delete user"]);
            exit();
        }
        break;


}

$mysqli->close();

?>