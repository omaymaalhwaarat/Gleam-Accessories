<?php
include('../db.php');


$query_discounts = "SELECT * FROM products WHERE discount > 0 LIMIT 3";
$result_discounts = $conn->query($query_discounts);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            background-color: #f5f5f5;
            color: #333;
        }

        .welcome-title {
            font-weight: 600;
            color: black;
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
            color: black;
            opacity: 0.5;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 20px;
        }

        .btnn {
            align-self: center;
            border: solid 0.1px;
            border-radius: 10px;
            text-decoration: none;
            color: black;
            padding: 5px 10px;
            transition: opacity 0.3s ease;
        }

        .btnn:hover {
            opacity: 0.5;
        }

        .btnn-see {
            display: inline-block;
            background-color: white;
            color: black;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 10px;
            border: 0.1px solid black;
            margin-top: 15px;
        }

        .btnn-see:hover {
            opacity: .7;
        }

        .card {
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            height: 100%;

        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 200px;

            object-fit: cover;

            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }


        .card-body {

            align-items: center;
            padding: 15px;
            flex-grow: 1;
            text-align: center;
        }

        .card-body .btnn {
            margin: 0 5px;

        }

        .card-body .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;

        }

        .card-body h4 {
            margin: 10px 0;
        }

        .card-body p {
            margin: 10px 0;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60px;

        }

        .container {
            background-color: white;
            padding-top: 30px;
            text-align: center;
            margin-top: 50px;
            padding-bottom: 20px;
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
            }
        }
    </style>
</head>

<body>

    <?php
    $query_women = "SELECT * FROM products WHERE category_id = 1 LIMIT 3";
    $result_women = $conn->query($query_women);

    $query_men = "SELECT * FROM products WHERE category_id = 2 LIMIT 3";
    $result_men = $conn->query($query_men);

    $query_kids = "SELECT * FROM products WHERE category_id = 3 LIMIT 3";
    $result_kids = $conn->query($query_kids);
    ?>

    <div class=" mt-3" style="border-bottom: solid .1px ;">
        <ul class="nav">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../index.php" style="color: black;">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../shop_page/store.php" style="color: black;opacity: .5;">Store</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../feedback.html" style="color: black;">Feedback</a>
            </li>
        </ul>
    </div>

    <!-- -------------------discount ----------------------------->

    <div style="text-align: center; margin-top: 80px;">
        <h1 class="welcome-title">Welcome to Gleam Accessories Store</h1>
        <h3 class="welcome-subtitle">We have been creating high-quality, handcrafted jewelry for over a decade,
            upholding the same passion and values!</h3>
    </div>
    <div class="container">
        <h3>Discounts</h3>
        <div class="cards">
            <?php while ($x = $result_discounts->fetch_assoc()): ?>
            <div class="card">
                <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $x['name']; ?>
                    </h4>
                    <p class="card-text">
                        <?php echo $x['description']; ?>
                    </p>
                    <p class="card-text" style="color: red; font-weight: bold;">
                        <del>
                            <?php echo $x['price_cost']; ?> JD
                        </del> <br>
                        <?php echo $x['price_cost'] - ($x['price_cost'] * $x['discount'] / 100); ?> JD
                    </p>

                    <div>
                        <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn">Shop Now</a>
                        <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>
                    </div>




                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <a href="discountspage.php" class="btnn-see">See all</a>
    </div>

    <!-------------------men cat---------------------------------------------------->
    <div class="container">
        <h3>Men</h3>
        <div class="cards">
            < <?php while ($x = $result_women->fetch_assoc()): ?>
                    <div class="card">
                        <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $x['name']; ?></h4>
                            <h4 class="card-title"><?php echo $x['price_cost']; ?> JD</h4>
                            <p class="card-text"><?php echo $x['description']; ?></p>
                            <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn" name="women_btn">Shop
                                Now</a>
                            <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>
                        </div>
                    </div>
                <?php endwhile; ?>
        </div>
        <a href="menpage.php" class="btnn-see">See all</a>
    </div>
    <!------------------------------------------------->
    <!---------------------------women car--------------------------->
    <div class="container">
        <h3>women</h3>
        <div class="cards">
            <?php while ($x = $result_men->fetch_assoc()): ?>
            <div class="card">
                <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $x['name']; ?>
                    </h4>
                    <h4 class="card-title">
                        <?php echo $x['price_cost']; ?> JD
                    </h4>
                    <p class="card-text">
                        <?php echo $x['description']; ?>
                    </p>
                    <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn">Shop Now</a>
                    <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <a href="womenpage.php" class="btnn-see">See all</a>
    </div>
    <!--------------------------------------->
    <!------------------------kids cat -------------------------->
    <div class="container">
        <h3>Kids</h3>
        <div class="cards">
            <?php while ($x = $result_kids->fetch_assoc()): ?>
                <div class="card">
                    <img class="card-img-top" src="<?php echo $x['image']; ?>" alt="<?php echo $x['name']; ?>">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $x['name']; ?></h4>
                        <h4 class="card-title"><?php echo $x['price_cost']; ?> JD</h4>
                        <p class="card-text"><?php echo $x['description']; ?></p>
                        <a href="../user/product.php?id=<?php echo $x['id']; ?>" class="btnn">Shop Now</a>
                        <a href="../user/reviews.php?id=<?php echo $x['id']; ?>" class="btnn"> Reviews</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="kidspage.php" class="btnn-see">See all</a>
    </div>

    <footer>
        <div class="forlogo">
            <img src="asset/home_img/logo1.png" alt="">
        </div>
        <div class="fot">
            <h3>GLEAM ACCESSORIES</h3>
            <div>
                <a href="#" style="margin-right: 40px;"><i class="fab fa-facebook"
                        style="font-size: 30px;color: black;"></i></a>
                <a href="#"><i class="fab fa-instagram" style="font-size: 30px;color: black;"></i></a>
            </div>
            <p>© 2025, GLEAM Inc. SHOP NOW WITH GLEAM</p>
        </div>
    </footer>
</body>

</html>