<?php
session_start();
include ('../db.php');
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product Not found";
        exit;
    }
} else {
    echo "ID for product not correct!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
        }
        body {
            background-color: #faf7f5;
            margin-top: 20px;
        }
        .container {
            text-align: center;
        }
        .product-title {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .product-description {
            font-size: 1.3rem;
            color: #555;
            margin-top: 15px;
        }
        .product-price {
            font-size: 1.8rem;
            font-weight: bold;
            color: #28a745;
            margin-top: 15px;
        }
        .product-image {
            border-radius: 10px;
            max-width: 100%;
            width: 100%;
            height: auto;
        }
        .btn-custom {
            background-color: white;
            color: black;
            font-weight: bold;
            border: solid 1px black;
            padding: 8px 16px;
            border-radius: 5px;
            transition: opacity 0.3s;
            font-size: 14px;
        }
        .btn-custom:hover {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1> Product Page</h1> 
        <div class="row mt-4 align-items-center">
            <div class="col-md-6">
                <img src="<?php echo $product['image']; ?>" class="product-image" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="col-md-6 text-start">
            <p class="product-description"> <?php echo $product['name']; ?> </p>
                <p class="product-description"> <?php echo $product['description']; ?> </p>
                <h4 class="product-price">Price: <?php echo $product['price_cost']; ?> JD</h4>
                <p class="fw-bold"> Quantity: <?php echo $product['quantity']; ?></p>
                <form action="cart.php" method="GET">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" value="1" min="1" class="form-control w-50">
                    <button type="submit" class="btn-custom mt-3">Add to cart</button>
                </form>
                <a href="../shop_page/store.php" class="btn-custom mt-3 d-inline-block">Back to store</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>