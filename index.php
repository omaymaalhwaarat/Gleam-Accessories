<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Document</title>
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

        .first {
            display: flex;
            min-height: 700px;
        }

        a:hover {
            opacity: .5;
        }

        .left {
            width: 80%;
        }

        .right {
            min-height: 700px;
            width: 100%;
        }

        .butuu {
            text-decoration: none;
            color: black;
            border: #333 solid .1px;
            padding: 10px;
        }

        .second {
            text-align: center;
            margin-top: 50px;
        }

        .cards {
            margin-top: 60px;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .card {
            width: 300px;

            height: 450px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;

            padding-bottom: 20px;
            text-align: center;
            box-sizing: border-box;
            border: 1px solid #ddd;

            border-radius: 8px;

            overflow: hidden;

        }

        .third {
            text-align: center;
            margin-top: 100px;
        }

        .card>img {
            width: 100%;

            height: 60%;

            object-fit: cover;

            border-bottom: 1px solid #ddd;

        }

        .card-title,
        .card-text {
            margin: 0;
            padding: 10px 15px;
        }

        .card .butuu {
            margin-top: auto;

        }

        .forth {
            margin-top: 60px;
            text-align: center;
            margin-bottom: 60px;
        }

        .container {
            background-color: white;
            padding-top: 30px;
            justify-items: center;
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
    <!-------------------------------first section---------------------------->

    <div class="first">
        <div class="left">
            <div class=" mt-3">
                <ul class="nav">
                    <li class="nav-item">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" style="color: black;opacity: .5;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop_page/store.php" style="color: black;">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="feedback.html" style="color: black;">Feedback</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" style="color: black;">Login</a>
                    </li>
                </ul>
            </div>
            <div style="text-align: center; margin-top: 200px;">
                <h1 style="font-size: 50px;">GLEAM ACCESSORIES of Precious Craft</h1>
                <h2 style="margin-bottom: 40px;">Because every piece caries a precious story</h2>
                <a href="shop_page/store.php" class="butuu"> shop now </a>
            </div>
        </div>
        <div class="right">
            <ul class="nav" style="justify-content: end;">
                <li class="nav-item">
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user/cart.php" style="color: black; opacity: .9;font-size: 30px;"><i
                            class="fa-solid fa-cart-shopping"></i>
                    </a>
                </li>
                <li class="nav-item" id="user">
                    <a class="nav-link" href="user/profile.php" style="color: black;opacity: .9;font-size: 30px"><i
                            class="fa-solid fa-user"></i> </a>
                </li>
            </ul>
            <img src="asset/home_img/hero-01.jpg" style="width: 100%; height: 700px;margin-top: -60px;" class="d-block">
        </div>
    </div>
    <!----------------------------------------------------------->
    <!------------------------------shop cat----------------------------->
    <div class="second">
        <h2 style="margin-bottom: 20px;">Shop by Category</h2>
        <a class="butuu" href="shop_page/store.php"> view all</a>
        <div class="cards">
            <div class="card">
                <img class="card-img-top" src="asset/home_img/categorie-02-300x300.jpg" alt=""> <!---card src -->
                <div class="card-body">
                    <h4 class="card-title">Women</h4>
                    <p class="card-text" style="margin-bottom: 30px;">Shop now from the Women's section </p>
                    <a href="shop_page/womenpage.php" class="butuu">See more</a>
                </div>
            </div>
            <div class="card">
                <img class="card-img-top" src="asset/home_img/pre.men.jpg" alt=""> <!---card src -->
                <div class="card-body">
                    <h4 class="card-title">Men</h4>
                    <p class="card-text" style="margin-bottom: 30px;">Shop now from the men's section </p>
                    <a href="shop_page/menpage.php" class="butuu">See more</a>
                </div>
            </div>
            <div class="card">
                <img class="card-img-top" src="asset/home_img/pre.kids.jpg" alt=""> <!---card src -->
                <div class="card-body">
                    <h4 class="card-title">Kids</h4>
                    <p class="card-text" style="margin-bottom: 30px;">Shop now from the kid's section</p>
                    <a href="shop_page/kidspage.php" class="butuu">See more</a>
                </div>
            </div>
        </div>
    </div>
    <!----------------------------------------------------------->
    <!-------------------------------see some items---------------------------->
    <div class="third">
        <img src="asset/home_img/image.png" alt="">
        <div style="display: flex;justify-content: center; margin-top: 30px;">
            <h2 style="width: 350px;">We make high-quality, handcrafted jewelry for over a decade, having the same
                passion & values!</h2>
        </div>
        <div style="display: flex;justify-content: center; margin-top: 15px; margin-bottom: 20px;">
            <p style="width: 350px;">With every creation, we remain dedicated to quality, artistry, and customer
                satisfaction. Join us in celebrating the craftsmanship and beauty of handmade jewelry that you can wear
                with pride.</p>
        </div>

    </div>
    <!------------------------------------------------------------------->
    <!---------------------------------shoping ---------------------------------->
    <div class="forth">
        <h2 style="margin-bottom: 40px;">Shoping with GLEAM accessories</h2>
        <div class="container">
            <div id="demo" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="align-items: center;">
                        <div class="card">
                            <img class="card-img-top" src="asset/home_img/pre.kids.jpg" alt=""> <!---card src -->
                            <div class="card-body">
                                <h4 class="card-title">Kids</h4>
                                <p class="card-text" style="margin-bottom: 30px;">Shop now from the kid's section</p>
                                <a href="shop_page/kidspage.php" class="butuu">See All</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" style="align-items: center;">
                        <div class="card">
                            <img class="card-img-top" src="asset/home_img/pre.men.jpg" alt=""> <!---card src -->
                            <div class="card-body">
                                <h4 class="card-title">Men</h4>
                                <p class="card-text" style="margin-bottom: 30px;">Shop now from the men's section </p>
                                <a href="shop_page/menpage.php" class="butuu">See All</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" style="align-items: center;">
                        <div class="card">
                            <img class="card-img-top" src="asset/home_img/categorie-02-300x300.jpg" alt="">
                            <!---card src -->
                            <div class="card-body">
                                <h4 class="card-title">Women</h4>
                                <p class="card-text" style="margin-bottom: 30px;">Shop now from the Women's section </p>
                                <a href="shop_page/womenpage.php" class="butuu">See All</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev"
                    style="margin-left: -100px;">
                    <span class="carousel-control-prev-icon" style="background-color: black;"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next"
                    style="margin-right: -100px;">
                    <span class="carousel-control-next-icon" style="background-color: black;"></span>
                </button>
            </div>
        </div>
    </div>
    <!------------------------------------------------------------------->
    <!-------------------footer-------------------------------------->
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
    <!------------------------------------------------------------------->

</body>

</html>