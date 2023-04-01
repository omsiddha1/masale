<?php
include('inc/connect.inc.php');

if(isset($_POST['password_reset_link']))
{
    $email=mysqli_real_esxape_string($con,$_post['email']);
    print_r($email);
    $token=md5(rand());

    $check_email="SELECT email FROM user WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(mysqli_run_rows($check_email_run)>0)
    {
        $row=mysqli_fetch_array($check_email_run);
        $get_name=$rows['firstname']
    }
    else
    {
        $_SESSION['status']="NO EMAIL FOUND";
        header("location: forgetpass.php");
        exit(0);
    }
} 
?>