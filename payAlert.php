<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" contant="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA_Compatible" content="le-edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/sFr">

</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1>Payment Alert</h1>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="exampleInputPasswords">Mobile Number</label>
                            <input type="number" class="form-control" id="number" name="number" placeholder="Enter Number">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPasword">Message</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="10"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</body>
</html>
<?php
$authKey = "421907A0uZgrGz66446835P1";
$senderId="LARKSPUR";
if(isset($_POST['sendmsg']))
{
    $mobileNumber = $_POST['number'];
    $msg = $_POST['message'];
$message=urlencode("Test message");
$route="default";
$postDate= array(
    'authkey'=> $authKey,
    'mobiles'=> $mobileNumber,
    'message'=>$message,
    'sender'=>$senderID,
    'route'=>$route,
);
$url="http://api.msg91.com/api/sendhttp.php";
$ch = curl_init($url);
curl_setopt_array($ch,array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => $postData
));
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
$response = curl_exec($ch);
if(curl_errno($ch))
{
    echo 'error:' .curl_error($ch);
}
curl_close($ch);

echo '<script>alert("Your Message Has been Sent to '.$mobileNumber.'by this '.$response.'")</script>';
}
?>