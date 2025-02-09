<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$total_price = 0;

if (isset($_POST['Payment'])) {
    $date = date('Y-m-d H:i:s');
    $status = 'Pending';


    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $result = $conn->query("SELECT price_cost, price_with_Revenue, discount FROM products WHERE id = $product_id");
        $product = $result->fetch_assoc();

        $discount_amount = $product['price_cost'] * ($product['discount'] / 100);
        $price_after_discount = $product['price_cost'] - $discount_amount;

        $total_price += $price_after_discount * $quantity;
    }

    // Insert into orders table
    $query = "INSERT INTO orders (date, status, amount, user_id) 
                VALUES ('$date', '$status', '$total_price', '$user_id')";
    if (mysqli_query($conn, $query)) {
        $order_id = mysqli_insert_id($conn);

        // Insert products into order_products table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $result = $conn->query("SELECT price_cost, price_with_Revenue, discount FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();

            $discount_amount = $product['price_cost'] * ($product['discount'] / 100);
            $price_after_discount = $product['price_cost'] - $discount_amount;

            $order_product_query = "INSERT INTO order_products (price, quantity, order_id, product_id) 
                                    VALUES ({$product['price_cost']} , $quantity, $order_id, $product_id)";
            mysqli_query($conn, $order_product_query);
        }

        // Clear cart session
        unset($_SESSION['cart']);

        // Update order status to "Completed"
        $update_status_query = "UPDATE orders SET status = 'Completed' WHERE id = $order_id";
        if (mysqli_query($conn, $update_status_query)) {
            echo "Payment Successful. Order status updated to 'Completed'.";
            header("Location: ../index.php?order_id=$order_id");
            exit();
        } else {
            echo "Error updating order status: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>check out</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        .title {
            font-weight: 600;
            color: black;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .title:hover {
            transform: scale(1.1);
            color: #333;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
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

        .btnn {
            border: solid .1px;
            border-radius: 10px;
            text-decoration: none;
            color: black;
            padding: 5px;
        }

        .btnn:hover {
            opacity: .5;
        }

        .card {
            width: 300px;
            margin: 40px;
            text-align: center;
        }

        .card>img {
            width: 100%
        }

        .container {
            background-color: white;
            padding-top: 30px;
            text-align: center;
            margin-top: 50px;
        }

        a:hover {
            opacity: .5;
        }

        ul>li {
            font-size: 20px;
        }

        .nav {
            justify-content: center;
        }

        .inputt {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 80%;
            padding: 10px;
            border: solid .1px;
            border-radius: 10px;
            color: black;
            background-color: white;
            font-size: 16px;
        }

        button:hover {
            opacity: .5;
        }

        .formbox {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class=" mt-3" style="border-bottom: solid .1px ;">
        <ul class="nav">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.html" style="color: black;">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../shop_page/store.php" style="color: black">Store</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../feedback.html" style="color: black;">Feedback</a>
            </li>
        </ul>
    </div>
    <div style="text-align: center; margin-top: 40px;" class="title">
        <h1>" We'd love to hear from you! Share your feedback. "</h1>
    </div>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>order</th>
                    <th>item name</th>
                    <th>cost</th>
                    <th>date</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                $counter = 1;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $result = $conn->query("SELECT name, price_cost , discount FROM products WHERE id = $product_id");
                        $product = $result->fetch_assoc();
                        $discount_amount = $product['price_cost'] * ($product['discount'] / 100);
                        $price_after_discount = $product['price_cost'] - $discount_amount;
                        $total_price += $price_after_discount * $quantity;
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $price_after_discount; ?>$</td>
                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            <td><?php echo 'Pending'; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </tbody>
        </table>
    </div>
    <div style="display: flex; justify-content: center;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Total cost</h4>
                <p class="card-text">Total: <strong><?php echo number_format($total_price, 2); ?></strong> JD</p>
            </div>
        </div>
    </div>

    </div>
    <div class="formbox">
        <div style="text-align: center;">
            <h2>Payment Details</h2>
        </div>
        <form method="post">
            <label>Name in card:</label><br>
            <input class="inputt" type="text" name="name_in_card" placeholder="Name in card ..." required>
            <label>card number:</label><br>
            <input class="inputt" type="text" name="card_number" placeholder="card number ..." required>
            <div>
                <label>Expiry:</label><br>
                <input type="text" name="Expiry_m" size="2" placeholder="MM" required>
                <input type="text" name="Expiry_y" size="4" placeholder="YYYY" required>
            </div>
            <label>CVV/CVC:</label><br>
            <input class="inputt" type="password" name="CVV" placeholder="CVV/CVC" required>
            <div style="text-align: center;">
                <button type="submit" name="Payment">Submit</button>
            </div>
        </form>

    </div>
    <div class="mt-3" style="border-bottom: solid 0.1px;">
        <ul class="nav">
            <li class="nav-item">
                <a href="../shop_page/store.php" class="btnn">Back to home</a>
            </li>
        </ul>
    </div>
</body>

</html>