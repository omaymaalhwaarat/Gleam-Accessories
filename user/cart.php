<?php
session_start();
include ('../db.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
    
    $_SESSION['cart'][$product_id] = $quantity;

    header("Location: cart.php");
    exit;
}

// Remove product from cart
if (isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['id'])) {
    unset($_SESSION['cart'][$_GET['id']]); // Remove product from cart
    header("Location: cart.php");
    exit;
}

$cart_products = [];
$total_price = 0; 
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart'])); 
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $product_quantity = $_SESSION['cart'][$row['id']];
        $row['quantity'] = $product_quantity; 

        $discount_amount = $row['price_cost'] * ($row['discount'] / 100);
        $price_after_discount = $row['price_cost'] - $discount_amount;
        
        $row['total_price'] = $price_after_discount * $product_quantity; 
        $row['price_after_discount'] = $price_after_discount; 
        $cart_products[] = $row;
        $total_price += $row['total_price']; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
        }
        body {
            background-color: #faf7f5;
        }
        table {
            border: solid 0.1px black;
            text-align: center;
        }
        table > thead > tr > th {
            font-weight: bold;
        }
        a {
            text-decoration: none;
            color: black;
            font-weight: bolder;
        }
        button {
            background-color: white;
        }
        button:hover, a:hover {
            opacity: 0.7;
        }
        .nav-link {
            color: black;
        }
    </style>
</head>
<body>
    <div class="mt-3" style="border-bottom: solid 0.1px;">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="../shop_page/store.php">⬅️ Continue Shopping</a>
            </li>
        </ul>
    </div>
    <div class="container mt-5 text-center">
        <h1>🛒 Shopping Cart</h1>
    </div>
    <div class="container mt-5">
        <?php if (!empty($cart_products)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Original Price</th>
                        <th>Discounted Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_products as $product): ?>
                        <tr>
                            <td><img src="<?php echo $product['image']; ?>" width="50"></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['price_cost']; ?> JD</td>
                            <td><?php echo number_format($product['price_after_discount'], 2); ?> JD</td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td><?php echo number_format($product['total_price'], 2); ?> JD</td>
                            <td><a href="cart.php?action=remove&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm">❌</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4>Total: <?php echo number_format($total_price, 2); ?>$</h4>
            <a href="checkout.php" class="btn btn-dark">Proceed to Checkout</a>
        <?php else: ?>
            <p class="text-center">Your cart is empty! 🛍️</p>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
$conn->close();
?>
