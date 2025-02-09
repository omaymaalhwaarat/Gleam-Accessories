<?php
require 'db.php';

session_start();

//Get all the orders
$stmt = "SELECT * FROM orders ORDER BY DESC";
$orders = $mysqli->query($stmt)->fetch_all(MYSQLI_ASSOC);


//Get the num of completed orders
$totalIncomeQuery = "WITH total_orders AS (
    SELECT
        o.id AS orderId,
        o.date AS order_date,
        p.name,
        p.price_cost,
        p.price_with_Revenue,
        op.discount,
        op.quantity
    FROM order_products op
    INNER JOIN orders o ON op.order_id = o.id
    INNER JOIN products p ON op.product_id = p.id
    WHERE o.status = 'completed'
),
Analytics AS (
    SELECT
        T.orderId,
        T.order_date,
        SUM(
            CASE 
                WHEN T.discount IS NOT NULL 
                THEN (T.price_with_Revenue * (100 - T.discount) / 100) * T.quantity
                ELSE T.price_with_Revenue * T.quantity 
            END
        ) AS TotalIncome,
        SUM(T.price_cost * T.quantity) AS ProductCosts
    FROM total_orders T
    GROUP BY T.orderId, T.order_date
)
SELECT 
    a.orderId,
    a.order_date,
    (a.TotalIncome - a.ProductCosts) AS net_profits
FROM Analytics a
GROUP BY a.order_date
HAVING DATE(a.order_date) = CURDATE();";
$totalIncomeResult = $mysqli->query($totalIncomeQuery)->fetch_assoc();


//Get num of categories
$stmt = "SELECT COUNT(*) AS number_of_categories FROM categories";
$totalCategories = $mysqli->query($stmt)->fetch_assoc();


//Get num of orders
$stmt = "SELECT COUNT(*) AS total_orders FROM orders";
$totalorders = $mysqli->query($stmt)->fetch_assoc();

//Get num of customers
$stmt = "SELECT COUNT(*) AS total_customers FROM users";
$totalCustomers = $mysqli->query($stmt)->fetch_assoc();


//Get daily revenue

$stmt = "SELECT DATE(date) AS day, SUM(amount_after_discount) AS daily_income FROM orders WHERE status = 'completed' GROUP BY DATE(date) ORDER BY DATE(date) ASC;";
$totalRevenue = $mysqli->query($stmt)->fetch_all(MYSQLI_ASSOC);

?>