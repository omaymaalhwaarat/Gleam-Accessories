<?php
include('../db.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id == 0) {
    echo "<script>alert('Invalid product ID');</script>";
    exit();
}

$user_query = $conn->query("SELECT username FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();
$username = $user['username'] ?? 'Guest';

$product_query = $conn->query("SELECT name FROM products WHERE id = $product_id");
if ($product_query->num_rows > 0) {
    $product = $product_query->fetch_assoc();
    $product_name = $product['name'];
} else {
    $product_name = 'Unknown Product';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = $conn->real_escape_string($_POST['comment']);

    if ($rating > 0 && $comment != '') {
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);

        if ($stmt->execute()) {
            echo "<script>alert('Review submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please select a rating and provide a comment.');</script>";
    }
}

$sql = "SELECT reviews.rating, reviews.comment, reviews.created_at, users.username 
        FROM reviews 
        JOIN users ON reviews.user_id = users.id 
        WHERE reviews.product_id = $product_id
        ORDER BY reviews.created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review for <?php echo htmlspecialchars($product_name); ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 20px; background-color: #f4f4f4; }
        form { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #fff; }
        input, textarea { width: 100%; padding: 8px; margin: 10px 0; border-radius: 5px; }
        button { background: #28a745; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 10px; }
        button:hover { background: #218838; }
        .reviews { max-width: 600px; margin: auto; text-align: left; background-color: #fff; padding: 10px; border-radius: 10px; }
        .review { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .review strong { display: block; font-size: 1.2em; margin-bottom: 5px; }
        .rating { color: #ff9800; font-size: 1.2em; }
        .comment { margin: 5px 0; font-size: 1em; }
        .timestamp { font-size: 0.8em; color: gray; }
        .stars input { display: none; }
        .stars label { font-size: 30px; color: #ccc; cursor: pointer; }
        .stars input:checked ~ label { color: #ff9800; }
        .btnn{
            border: solid .1px;
            border-radius: 10px;
            text-decoration: none;
            color: black;
            background-color: #ccc;
            padding: 5px;
            margin-top: 20px;

        }
        .btnn:hover {
            opacity: .5;
        }
    </style>
</head>
<body>
    <h2>Review for <?php echo htmlspecialchars($product_name); ?></h2>
    <p>Reviewed by: <?php echo htmlspecialchars($username); ?></p>
    
    <form method="POST">
        <div class="stars">
            <input type="radio" name="rating" id="star5" value="5"><label for="star5">&#9733;</label>
            <input type="radio" name="rating" id="star4" value="4"><label for="star4">&#9733;</label>
            <input type="radio" name="rating" id="star3" value="3"><label for="star3">&#9733;</label>
            <input type="radio" name="rating" id="star2" value="2"><label for="star2">&#9733;</label>
            <input type="radio" name="rating" id="star1" value="1" required><label for="star1">&#9733;</label>
        </div>
        <textarea name="comment" placeholder="Write your comment" required></textarea>
        <button type="submit">Submit Review</button>
    </form>

    <h2>Recent Reviews</h2>
    <div class="reviews">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review">
                    <p><?php echo htmlspecialchars($username); ?></p>
                    
                    <p class="comment">"<?php echo nl2br(htmlspecialchars($row['comment'])); ?>"</p>
                    <p class="rating">
                        <?php echo str_repeat("&#9733;", $row['rating']); ?>
                    </p>
                    <small class="timestamp">Posted on: <?php echo $row['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet.</p>
        <?php endif; ?>
    </div>
    <button class="btnn" onclick="window.location.href='../shop_page/store.php'">Back to store</button>

</body>
</html>
