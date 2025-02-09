
<?php 
    class Login{
        public $email;
        public $password;
        public $f_name;
        public $l_name;
        public $valid = true ;
        public function __construct($email,$password){
            $this->email = $email;
            $this->password = $password;
            $this->login();
        }
        public function login(){
            session_start();
            $this->islogin();
            $this->check();
        }
        public function check(){  
                include('conn.php');
                $query = "SELECT * FROM users WHERE email = '$this->email'";
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);
                if (!$user) {
                    $this->valid = false;
                    die("User not found"); 
                } 
                if (trim($this->password) == trim($user['password'])) { 
                    $this->f_name =  $user['f_name'];
                    $this->l_name = $user['l_name'];
                } else {
                    $this->valid = false;
                    echo "Wrong password";
                }
                if($this->valid){
                    $_SESSION['email'] = $this->email;
                    $_SESSION['password'] = $this->password;
                    $_SESSION['name'] = $this->f_name ."". $this->l_name;
                    if($user['role']=="admin"){
                        header("location: admindash.php");
                    }
                    else{
                        header('location: index.php');
                    }
                }
            }
            public function islogin(){
                if(isset($_SESSION['email'])){
                    if($this->email == $_SESSION['email']){
                        $this->valid = false;
                        echo "this user logging ";
                    }
                }
            }
            public function logout(){
                session_destroy();
            }
            public function show(){
                echo "$this->email";
                echo "$this->password";
            }
        }
        if(isset($_POST['login'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = new Login($email,$password );
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>add users</title>
    <style>
      *{
            font-family: "Cormorant Garamond", serif;
            font-weight: 300;
            font-style: normal;
        }
        body {
            background-color: #faf7f5;
            color: #333;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 250px;
        }
        h2 {
            text-align: center;
            color: #d4af37;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 80%;
            padding: 10px;
            border: solid .1px ;
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
        .err{
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" onsubmit="return validation(event)">
            <div>
                <label>Email:</label><br>
                <input type="email" name="email" id="email" placeholder="Email ..." required>
                <p id="email_error" class="err"></p>
            </div>
            <div>
                <label>Password:</label><br>
                <input type="password" name="password" id="password" placeholder="Password ...." required>
                <p id="password_error" class="err"></p>
            </div>
            <div style="text-align: center;">
                <button type="submit" name="login">Submit</button>
                <p>Don't have an account? <a href="signup.html">Signup page</a></p>
            </div>
        </form>
    </div>

    <script>
        function validation(event){
            event.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const email_error = document.getElementById("email_error");
            const password_error = document.getElementById("password_error");
            let valid = true;
            const emailtest = /\S+@\S+\.\S+/;
            if (emailtest.test(email)) {
                email_error.textContent = ""; 
            } else {
                valid = false;
                email_error.textContent = "Ex: test@test.com";
            }
            if (password.length >= 8) {
                password_error.textContent = "";
            } else {
                valid = false;
                password_error.textContent = "Password should be 8 or more characters.";
            }
            if (valid) {
                event.target.submit();
            }
        }
    </script>
</body>
</html>