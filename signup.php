<?php
ob_start();
?>

<?php

class Signup
{
    public $email;
    public $password;
    public $conf_pass;
    public $name;
    public $phone;
    public $address;
    public $first_name;
    public $second_name;
    public $last_name;
    public $valid = true;
    public $errors = [];

    public function __construct($email, $password, $conf_pass, $name, $phone, $address)
    {
        $this->email = $email;
        $this->password = $password;
        $this->conf_pass = $conf_pass;
        $this->name = $name;
        $this->phone = $phone;
        $this->address = $address;
        $this->splitName();
    }
    public function splitName()
    {
        $nameParts = explode(" ", $this->name);
        if (count($nameParts) >= 3) {
            $this->first_name = $nameParts[0];
            $this->second_name = $nameParts[1];
            $this->last_name = $nameParts[2];
        } else {
            $this->valid = false;
            $this->errors['name'] = "Full name must contain at least three words (first name, second name, and last name).";
        }
    }
    public function validate()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->valid = false;
            $this->errors['email'] = "Please enter a valid email address (e.g., test@test.com).";
        }
        if (strlen($this->password) < 8) {
            $this->valid = false;
            $this->errors['password'] = "Password must be at least 8 characters long.";
        }
        if ($this->password != $this->conf_pass) {
            $this->valid = false;
            $this->errors['confirm'] = "Passwords do not match.";
        }
        if (!preg_match("/^(079|078|077)[0-9]{7}$/", $this->phone)) {
            $this->valid = false;
            $this->errors['phone'] = "Phone number must start with 079, 078, or 077, followed by 7 digits.";
        }
        if (empty(trim($this->address))) {
            $this->valid = false;
            $this->errors['address'] = "Address is required.";
        }
    }

    public function saveToDatabase()
    {
        if ($this->valid) {
            include('db.php');
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users`( `first_name`, `second_name`, `last_name`, `email`, `phone`, `address`, `username`, `password`, `created_at`,`role`) 
            VALUES ('$this->first_name','$this->second_name','$this->last_name','$this->email','$this->phone','$this->address','$this->first_name','$hashedPassword', NOW() , 'user')";
            if (mysqli_query($conn, $sql)) {
                header("location: http://localhost/fullproject/login.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
    public function getErrors()
    {
        return $this->errors;
    }
}

?>
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
    <title>sign up</title>
    <style>
        * {
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
            font-style: normal;
        }

        body {
            padding: 0;
            margin: 0;
            background-color: #faf7f5;
            background-image: url("log.png");
            background-size: auto;
            background-repeat: no-repeat;
            color: #333;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: black;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 80%;
            padding: 10px;
            border: solid .1px;
            color: black;
            background-color: white;
            font-size: 16px;
        }

        button:hover {
            opacity: .5;
        }

        a {
            color: #d4af37;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .err {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="post" onsubmit="return signvalid(event)">
            <div>
                <label>Full Name:</label><br>
                <input type="text" name="name" id="name" placeholder="Full Name ..." required>
                <p id="name_err" class="err"> </p>
            </div>
            <div>
                <label>Email:</label><br>
                <input type="email" name="email" id="email" placeholder="Email ..." required>
                <p id="email_error" class="err"> </p>
            </div>
            <div>
                <label>Password:</label><br>
                <input type="password" name="password" id="password" placeholder="Password ...." required>
                <p id="password_error" class="err"> </p>
            </div>
            <div>
                <label>Confirm Password:</label><br>
                <input type="password" name="conf_pass" id="conf_pass" placeholder="Confirm Password ...." required>
                <p id="confirm_error" class="err"> </p>
            </div>
            <div>
                <label>Phone number:</label><br>
                <input type="text" name="phone" id="phone" placeholder="Phone number ..." required>
                <p id="phone_error" class="err"> </p>
            </div>
            <div>
                <label>Address:</label><br>
                <input type="text" name="address" id="address" placeholder="Address ..." required>
                <p id="address_error" class="err"> </p>
            </div>
            <div style="text-align: center;">
                <button type="submit" name="signup">Submit</button>
                <p>Already have an account? <a href="login.php">Login page</a></p>
            </div>
        </form>
    </div>

    <script>
        function signvalid(event) {

            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const conf_pass = document.getElementById("conf_pass").value;
            const phone = document.getElementById("phone").value;
            const address = document.getElementById("address").value;
            const name_err = document.getElementById("name_err");
            const email_error = document.getElementById("email_error");
            const password_error = document.getElementById("password_error");
            const confirm_error = document.getElementById("confirm_error");
            const phone_error = document.getElementById("phone_error");
            const address_error = document.getElementById("address_error");
            let valid = true;
            const nameParts = name.trim().split(" ");
            if (nameParts.length == 3) {
                name_err.textContent = "";
            } else {
                valid = false;
                name_err.textContent = "Full name must contain exactly two words.";
            }
            const emailtest = /\S+@\S+\.\S+/;
            if (emailtest.test(email)) {
                email_error.textContent = "";
            } else {
                valid = false;
                email_error.textContent = "Please enter a valid email address (ex: test@test.com).";
            }
            if (password.length >= 8) {
                password_error.textContent = "";
            } else {
                valid = false;
                password_error.textContent = "Password must be at least 8 characters long.";
            }
            if (password == conf_pass) {
                confirm_error.textContent = "";
            } else {
                valid = false;
                confirm_error.textContent = "Passwords do not match.";
            }
            const phoneRegex = /^(079|078|077)[0-9]{7}$/;
            if (phoneRegex.test(phone)) {
                phone_error.textContent = "";
            } else {
                valid = false;
                phone_error.textContent = "Phone number must start with 079, 078, or 077, followed by 7 digits.";
            }
            if (address.trim() == "") {
                valid = false;
                address_error.textContent = "Address is required.";
            } else {
                address_error.textContent = "";
            }
            if (!valid) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>
<?php
if (isset($_POST['signup'])) {
    ob_start(); // Start output buffering

    $email = $_POST['email'];
    $password = $_POST['password'];
    $conf_pass = $_POST['conf_pass'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $signup = new Signup($email, $password, $conf_pass, $name, $phone, $address);
    $signup->validate();

    if ($signup->valid) {
        $signup->saveToDatabase();
    } else {
        $errors = $signup->getErrors();
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }

    ob_end_flush(); // Flush the output buffer
}
?>