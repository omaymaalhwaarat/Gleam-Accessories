<?php
require 'db.php';

session_start();

//Get all the orders
$stmt = "SELECT * FROM orders";
$orders = $mysqli->query($stmt)->fetch_all(MYSQLI_ASSOC);


//Get the num of completed orders
$totalIncomeQuery = "SELECT SUM(COALESCE(amount_after_discount, amount)) AS total_income FROM Orders WHERE status = 'Completed';";
$totalIncomeResult = $mysqli->query($totalIncomeQuery)->fetch_assoc();



//Total sales
$totalSales = "SELECT COUNT(id) AS total_sales FROM orders WHERE status='Completed'";
$totalSalesResult = $mysqli->query($totalSales)->fetch_assoc();


//Get num of categories
$stmt = "SELECT COUNT(id) AS number_of_categories FROM categories";
$totalCategories = $mysqli->query($stmt)->fetch_assoc();


//Get num of orders
$stmt = "SELECT COUNT(id) AS total_orders FROM orders";
$totalorders = $mysqli->query($stmt)->fetch_assoc();

//Get num of customers
$stmt = "SELECT COUNT(id) AS total_customers FROM users";
$totalCustomers = $mysqli->query($stmt)->fetch_assoc();


//Get daily revenue

$stmt = "SELECT DATE(date) AS day, SUM(amount_after_discount) AS daily_income FROM orders WHERE status = 'completed' GROUP BY DATE(date) ORDER BY DATE(date) ASC";
$totalRevenue = $mysqli->query($stmt)->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sales dashboadr</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * {
            font-family: "Cormorant Garamond", 'Courier New', Courier, monospace;
            font-weight: 400;
            font-size: 16px;
            font-style: normal;

        }

        body {
            background-color: #faf7f5;
        }

        table {
            border: solid .1px black;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: space-evenly;
            margin-top: 60px;
        }

        .card {
            width: 300px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            padding: 20px;
            text-align: center;
        }

        p {
            font-size: xx-large;
        }

        a:hover {
            opacity: .7;
        }

        ul>li {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class=" mt-3" style="border-bottom: solid .1px ;">
        <ul class="nav">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://127.0.0.1:5500/admin_front/admindash.html" style="color: black;">admin
                    dashboard</a>

            </li>

        </ul>
    </div>

    <div style="text-align: center; " class="mt-5">
        <h1>Sales dashboard</h1>
    </div>
    <div class="container mt-5" style="text-align: center;">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Number of orders</th>
                    <th>Total Sales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo date('F j,Y') ?></td>
                    <td><?php print_r($totalorders['total_orders']) ?></td>
                    <td>
                        <?php print_r($totalSalesResult['total_sales']) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="cards">
        <div class="card">
            <div>
                <h4 class="card-title">Total Income</h4>
            </div>
            <div>
                <p class="card-text"> <?php print_r($totalIncomeResult['total_income']) ?></p>
            </div>
        </div>
        <div class="card">
            <div>
                <h4 class="card-title">Number of categories</h4>
            </div>
            <div>
                <p class="card-text"><?php print_r($totalCategories['number_of_categories']) ?></p>
            </div>
        </div>
        <div class="card">
            <div>
                <h4 class="card-title">Number of orders</h4>
            </div>
            <div>
                <p class="card-text"><?php print_r($totalorders['total_orders']) ?></p>
            </div>
        </div>
        <div class="card">
            <div>
                <h4 class="card-title">Number of clients</h4>
            </div>
            <div>
                <p class="card-text"><?php print_r($totalCustomers['total_customers']) ?></p>
            </div>
        </div>
    </div>
</body>

</html>