<?php
include 'conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

include('./conn.php');
ob_start();
if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 3")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
}
ob_end_flush();

    include 'conn.php';
    $msg = "";

    if (isset($_GET['verification'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
            $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
            
            if ($query) {
                $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
            }
        } else {
            header("Location: adminlogin.php");
        }
    }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email']=$email;

    $sql = "SELECT * FROM users WHERE email='$email'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data && password_verify($password, $data['password'])) {
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minute"));
        $subject= "Your OTP for Login";
        $message="Your OTP is: $otp";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'masakarobert966@gmail.com'; //host email 
        $mail->Password = 'hcshthlhgrqvvrtz'; // app password of your host email
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom('masakarobert966@gmail.com', 'Larkspur Agencies');//Sender's Email & Name
        $mail->addAddress($email,$name); //Receiver's Email and Name
        $mail->Subject = ("$subject");
        $mail->Body = $message;
        $mail->send();

        $sql1 = "UPDATE users SET otp='$otp', otp_expiry='$otp_expiry' WHERE id=".$data['id'];
        $query1 = mysqli_query($conn, $sql1);

        $_SESSION['temp_user'] = ['id' => $data['id'], 'otp' => $otp];
        header("location:index.php?page=otp_verification");
        exit();
    } else {
        ?>
        <script>
           alert("Invalid Email or Password. Please try again.");
                function navigateToPage() {
                    window.location.href = 'adminlogin.php';
                }
                window.onload = function() {
                    navigateToPage();
                }
        </script>
        <?php 
    
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $_SESSION['system']['name'] ?></title>
 	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            margin-left: 400px;
            border: 1px solid black;
            width: 440px;
            padding: 20px;
            margin-top: 40px;
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
        form{
            margin-left: 50px;
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
            background-color: blue;
            border: 1px solid blue;
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
        <form method="post" action="adminlogin.php">
            <label for="email">Email</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br><br>
            <input type="submit" name="login" value="Login"><br><br>
            <label>Don't have an account? </label><a href="registration.php">Sign Up</a>
        </form>
    </div>
    <script>
		$('#login-form').submit(function(e){
		e.preventDefault()
        $('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax3.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
            success:function(resp){
				if(resp !== 1){
					location.href ='index.php?page=otp_verification';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</body>
</html>


