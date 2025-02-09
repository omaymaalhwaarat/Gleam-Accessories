<?php
include ('../db.php');

$query_discounts = "SELECT * FROM products WHERE discount > 0";
$result_discounts = $conn->query($query_discounts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discounted Products</title>
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
        
    .cards{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }
    .btnn{
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

.btnn:hover {
     opacity: .5;
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
    </style>
</head>
<body>

    <div class="mt-3" style="border-bottom: solid .1px;">
        <ul class="nav">
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


    <div class="container">
        <h3>Discounted Products</h3>
        <div class="cards">
            <?php while ($x = $result_discounts->fetch_assoc()): ?>
                <div class="card">
                    <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                    <div class="card-body">
                        <h4 class="card-title"> <?php echo $x['name']; ?> </h4>
                        <p class="card-text"> <?php echo $x['description']; ?> </p>
                        <p class="card-text" style="color: red; font-weight: bold;"> 
                            <del><?php echo $x['price_cost']; ?> JD</del> <br>
                           <?php echo $x['price_cost'] - ($x['price_cost'] * $x['discount'] / 100); ?> JD
                        </p>
                        <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn">Shop Now</a>
                        <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>