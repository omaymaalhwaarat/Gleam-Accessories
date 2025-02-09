<?php
include ('../db.php');

$search = "";
$min_price = "";
$max_price = "";

$query_conditions = [];
$query_params = [];
$query_types = "";

// البحث بالاسم
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $query_conditions[] = "name LIKE CONCAT('%', ?, '%')";
    $query_params[] = $search;
    $query_types .= "s";
}

// الفلترة بالسعر
if (isset($_GET['price']) && !empty($_GET['price'])) {
    $price_input = trim($_GET['price']);
    
    if (preg_match('/^(\d+)-(\d+)$/', $price_input, $matches)) {
        $min_price = $matches[1];
        $max_price = $matches[2];

        if (is_numeric($min_price)) {
            $query_conditions[] = "price_cost >= ?";
            $query_params[] = (int)$min_price;
            $query_types .= "i";
        }
        if (is_numeric($max_price)) {
            $query_conditions[] = "price_cost <= ?";
            $query_params[] = (int)$max_price;
            $query_types .= "i";
        }
    }
}

// بناء الاستعلام النهائي
$search_query = "SELECT * FROM `products` WHERE category_id = 1";
if (!empty($query_conditions)) {
    $search_query .= " AND " . implode(" AND ", $query_conditions);
}

$stmt = mysqli_prepare($conn, $search_query);

if (!empty($query_params)) {
    mysqli_stmt_bind_param($stmt, $query_types, ...$query_params);
}

mysqli_stmt_execute($stmt);
$result_query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        *{
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
            font-style: normal;
        }
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        .welcome-title {
        font-weight: 600;
        color: #C9A96E;
        transition: transform 0.3s ease, color 0.3s ease; 
    }

    .welcome-title:hover {
        transform: scale(1.1); 
        color: #333; 
    }

    .welcome-subtitle {
        font-weight: 400;
        color: #555; 
        transition: color 0.3s ease; 
    }

    .welcome-subtitle:hover {
    color: #C9A96E; 
}

    .cards{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }
    .btnn{
        border: solid .1px;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        padding: 5px;
    }
    .btnn:hover {
        opacity: .5;
    }
    .btnn-see {
    display: inline-block;
    background-color:white;
    color:black;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 10px;
    border: .1px solid black;
}

.btnn-see:hover {
     opacity: .7;
}
    .card{
        width:300px;
        margin: 40px;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
    }
    .card img {
        width: 100%;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    .card-body {
        padding: 15px;
        text-align: center;
    }
    .container{
        background-color: white;
        padding-top: 30px;
        text-align: center;
        margin-top: 50px;
        padding-bottom: 20px;
    }
    a:hover{
        opacity: .5;
    }
    ul>li{
        font-size: 20px;
    }
    .nav{
        justify-content: center;
    }


    
    /* تنسيق الفلاتر */
    .filter-form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .filter-form .form-control {
        border-radius: 25px;
        margin-right: 10px;
        border: 1px solid #ccc;
        padding: 10px;
        font-size: 14px;
    }

    .filter-form button {
        background-color: #C9A96E;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 25px;
        transition: background-color 0.3s ease;
    }

    .filter-form button:hover {
        background-color:rgb(123, 128, 132);
    }

    /* جعل الفلاتر تظهر بشكل عمودي على الشاشات الصغيرة */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-form .form-control {
            margin-bottom: 10px;
        }
    }
    @media (max-width: 768px) {
    .btnn-see {
        padding: 8px 15px; 
        font-size: 14px; 
        border-radius: 8px; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); 
    }
}

@media (max-width: 480px) {
    .btnn-see {
        padding: 6px 12px; 
        font-size: 13px; 
        border-radius: 6px; 
        width: 100%; 
        text-align: center;
    }}
</style>

</head>
<body>
                
    <div class=" mt-3" style="border-bottom: solid .1px ;">
        <ul class="nav">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.php" style="color: black;">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../shop_page/store.php" style="color: black;">Store</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../feedback.html" style="color: black;">Feedback</a>
            </li>
        </ul>
    </div>
            <!-- -----------------------------------filter and search ------------------------------------------->
    <form method="GET" action="" class="filter-form">
        <div class="row">
            <div class="col-md-6">  
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="price" class="form-control filter-box" 
                        placeholder="e.g: 200-500"
                        value="<?php echo (isset($_GET['price']) && !empty($_GET['price'])) ? htmlspecialchars($_GET['price']) : ''; ?>">
                    <button type="submit" class="btn btn-light">Filter</button>
                </div>
            </div>
        </div>
    </form>


    <div class="container">
        <h3>Men</h3>
        <div class="cards">
            <?php while ($x = $result_query->fetch_assoc()): ?>
                <div class="card">
                    <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                    <div class="card-body"> 
                        <h4 class="card-title"><?php echo $x['name']; ?></h4>
                        <h4 class="card-title"><?php echo $x['price_cost']; ?></h4>
                        <p class="card-text"><?php echo $x['description']; ?></p>
                        <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn" name="men_btn">Shop Now</a>
                        <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>