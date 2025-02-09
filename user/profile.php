<?php
session_start();

include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name, second_name, last_name, email, phone, address, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p class='text-danger text-center'>User not found</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_name = trim($_POST['first_name']);
    $s_name = trim($_POST['second_name']);
    $l_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $new_password = trim($_POST['password']);

    if (!empty($new_password)) {
        $password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        $password = $user['password'];
    }

    $stmt = $conn->prepare("UPDATE users SET first_name=?, second_name=?, last_name=?, email=?, phone=?, address=?, password=? WHERE id=?");
    $stmt->bind_param("sssssssi", $f_name, $s_name, $l_name, $email, $phone, $address, $password, $user_id);

    if ($stmt->execute()) {
        echo "<p class='text-success text-center'>Update information successfully</p>";

        $user['first_name'] = $f_name;
        $user['second_name'] = $s_name;
        $user['last_name'] = $l_name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        echo "<p class='text-danger text-center'> Some thing's wrong " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profail </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    </style>
</head>

<body>

    <div class="container mt-4">
        <h2 class="text-center"> Profile </h2>
        <form method="POST">
            <div class="mb-3">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control"
                    value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Second Name</label>
                <input type="text" name="second_name" class="form-control"
                    value="<?php echo htmlspecialchars($user['second_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control"
                    value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                    value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control"
                    value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Password (leave empty if you don't want to change it)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button name="update" type="submit" class="btn btn-dark">Update Information</button>
        </form>

        <br>
        <a href="../shop_page/store.php" class="btnn">Back to store</a>
        <a href="history.php" class="btnn">View History</a>
    </div>

</body>

</html>

<?php
$conn->close();
?>