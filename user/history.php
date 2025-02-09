<?php
session_start();
include('../db.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT orders.id, orders.date, products.name, order_products.quantity, order_products.price,products.image
        FROM orders 
        JOIN order_products ON orders.id = order_products.order_id 
        JOIN products ON order_products.product_id = products.id
        WHERE orders.user_id = ? AND orders.status='Completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>history</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
            font-style: normal;
        }

        body {
            background-color: #faf7f5;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
        }

        .btnn {
            display: inline-block;
            border: solid .1px;
            text-decoration: none;
            color: black;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            background-color: white;
            text-align: center;
        }

        .btnn:hover {
            opacity: 0.7;
        }

        .forlogo {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: #333 solid .1px;
            text-align: center;
        }

        .forlogo>img {
            height: 200px;
        }

        .fot {
            text-align: center;
        }
    </style>
</head>

<body>

    <h1 style="text-align: center; margin-top: 40px;">History Orders</h1>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
            <tr>
                <th>Order ID</th>
                <th>Image</th>
                <th>Date of order</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td><img src='" . $row["image"] . "' alt='Product Image' width='50'></td>
                <td>" . $row["date"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["quantity"] . "</td>
                <td>" . $row["price"] . "</td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align: center;'>Don't have orders</p>";
    }
    $conn->close();
    ?>

    <div class="mt-3 d-flex justify-content-center">
        <ul class=" nav">
            <li class="nav-item">
                <a href="../shop_page/store.php" class="btnn">Back to store</a>
            </li>
        </ul>
    </div>


</body>

</html>