<?php
session_start();
include 'conn.php';

if (isset($_SESSION['user_id'])) {
    header("Location: reports.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        ?>
            <script>
    alert("Registration Successful.");
    function navigateToPage() {
        window.location.href = 'adminlogin.php';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
        <?php 
    } else {
       echo "<script> alert('Registration Failed. Try Again');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            border: 1px solid black;
            width: 450px;
            padding: 20px;
            margin-left: 400px;
            margin-top: 50px;
        }
        form{
            margin-left: 50px;
        }
        input[type=text],input[type=password]{
            width: 300px;
            height: 20px;
            padding: 10px;
        }
        label{
            font-size: 20px;
            font-weight: bold;
        }
        a{
            text-decoration: none;
            font-weight: bold;
            font-size: 21px;
            color: blue;
        }
        a:hover{
            cursor: pointer;
            color: #007bff;
        }
        input[type=submit]{
            width: 70px;
            background-color:#007bff;
            border: 1px solid #007bff;
            color: white;
            font-weight: bold;
            padding: 7px;
            margin-left: 130px;
        }
        input[type=submit]:hover{
            background-color: #007bff;
            cursor: pointer;
            border: 1px solid #007bff;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="registration.php">
            <label for="username">Username:</label><br>
            <input type="text" name="username" placeholder="Enter Username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Password" required><br><br>
            <input type="submit" name="register" value="Register"><br><br>
            <label>Already have an account? </label><a href="adminlogin.php">Login</a>
        </form>
    </div>

</body>
</html>