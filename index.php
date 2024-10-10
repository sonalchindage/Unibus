<?php
	session_start();
    include('includes/enc.php');
	include('includes/config.php');
	include('includes/checklogin.php');
	check_logOut();
	if(isset($_POST['login']))
	{
        $username=$_POST['emailreg'];
        $password=$_POST['password'];
        // SELECT `adminid`, `username`, `email`, `password`, `reg_date`, `updation_date`, `access`, `adminName`, `clgName` FROM `admin` WHERE 1
        $stmt=$mysqli->prepare("SELECT username,email,password,adminid,clgName FROM admin WHERE (userName=? OR email=?) AND password=? ");
        $stmt->bind_param('sss',$username,$username,$password);
        if($stmt->execute()){
            $stmt -> bind_result($username,$email,$password,$adminid, $clgName );
            $rs=$stmt->fetch();
            $stmt->close();
            $_SESSION['adminId']=$adminid ;
            $_SESSION['clgName']=$clgName ;

            if($rs)
            {
                $adminid=$_SESSION['adminid'];
                // $uemail=$_SESSION['login'];
                // $ip=$_SERVER['REMOTE_ADDR'];
                $ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                // echo $ip;
                // echo $_SERVER['REMOTE_HOST'];
                $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
                $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
                $city = $addrDetailsArr['geoplugin_city'];
                $country = $addrDetailsArr['geoplugin_countryName'];
                // INSERT INTO `adminlog`(`ipId`, `adminid`, `ip`, `logintime`, `adminName`, `adminEmail`, `country`, `city`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]')
                $log="insert into adminlog(adminid,ip,adminName,adminEmail,country,city) values('$adminid','$ip','$username','$email','$country','$city')";
                $mysqli->query($log);
                if($log)
                {
                    header("location:admin/dashboard.php");
                }
            }else{
                $emailreg=$_POST['emailreg'];
                $password= encrypt($_POST['password']);
                $stmt2=$mysqli->prepare("SELECT email,password,id,userPrn,gender,clgName FROM userregistration WHERE (email=? OR userPrn=?) AND password=? AND userPrn IS NOT NULL AND emailValidate = 1");
                $stmt2->bind_param('sss',$emailreg,$emailreg,$password);
                $stmt2 -> bind_result($email,$password,$id,$userPrn,$gender,$clgName);
                if(!$stmt2->execute()){
                    echo "<script>alert('Invalid Username/Email or password');</script>";
                }
                $rs=$stmt2->fetch();
                $stmt2->close();
                $_SESSION['id']=$id;
                $_SESSION['login']=$email;
                $_SESSION['userPrn'] = $userPrn;
                $_SESSION['gender'] = $gender;
                $_SESSION['clgName'] = $clgName;
        
                $sQuery= "SELECT status FROM registration WHERE userPrn =?";
                $stmt=$mysqli->prepare($sQuery);
                $stmt->bind_param('s',$userPrn);
                $stmt->execute();
                $stmt -> bind_result($status);
                $res=$stmt->fetch();
                $stmt->close();
        
                $_SESSION['status'] = $status;//? $status: "";
        
                // echo $userPrn;
                // echo $status;
                if($rs){
                    $uip=$_SERVER['REMOTE_ADDR'];
                    $ldate=date('d/m/Y h:i:s', time());
                    $uid=$_SESSION['id'];
                    $uemail=$_SESSION['login'];
                    // $ip=$_SERVER['REMOTE_ADDR'];
                    $ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    // echo $ip;
                    // echo $_SERVER['REMOTE_HOST'];
                    $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
                    $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
                    $city = $addrDetailsArr['geoplugin_city'];
                    $country = $addrDetailsArr['geoplugin_countryName'];
                    $log="insert into userLog(userId,userEmail,userIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
                    $mysqli->query($log);
                    if($log)
                    {
                        header("location:dashboard.php");
                    }
                }else{
                    echo "<script>alert('Invalid Username/Email or password or Email is not validated');</script>";
                }
                
                // $rs=$stmt2->fetch();
                // $stmt2->close();
            }
        }else{
            
        }
		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/regStyle.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-3/assets/css/registration-3.css">
    <title>Document</title>
</head>
<body>
    <!-- Registration 3 - Bootstrap Brain Component -->
     <div class="login_cont">
        <section class="p-3 p-md-4 p-xl-5">
            <div id="contf" >
            <div class="row" style="border-radius: 15px;overflow: hidden;">
                <!-- rounded-start-4 -->
                <div class="col-12 col-md-6 text-dark-emphasis" id="im">
                    <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                        <h3 class="m-0">Welcome!</h3>
                        <!-- <img class="img-fluid rounded mx-auto my-4" loading="lazy" src="./student-dormitory-room-isometric-background-vector.jpg" width="245" height="80" alt="BootstrapBrain Logo"> -->
                        <p class="mb-0 fw-semibold">Not a member yet?<a href="email_validation.php" class="link-secondary text-info-emphasis text-decoration-none">Register now</a></p>
                    </div>
                </div>

                <!-- rounded-end-4 -->
                <div class="col-12 col-md-6 bsb-tpl-bg-lotion ">
                    <div class="p-3 p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                        <div class="mb-5">
                            <h2 class="h3">Login</h2>
                            <h3 class="fs-6 fw-normal text-secondary m-0">Enter your login details</h3>
                        </div>
                        </div>
                    </div>
                    <form action="" method="post">
                        <div class="row gy-3 gy-md-4 overflow-hidden">
                    
                        <div class="col-12">
                            <label for="emailreg" class="form-label">Email / Registration Number (User PRN)<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="emailreg" id="emailreg" placeholder="Email / Registration Number" required>
                        </div>
                        <div class="col-12">
                            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                            <input class="form-control" type="password" placeholder="Password" id="Password" name="password" required>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid">
                            <button type="submit" class="btn bsb-btn-xl btn-primary" name="login" value="login">Sign in</button>
                            </div>
                        </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                        <hr class="mt-5 mb-4 border-secondary-subtle">
                        <p class="m-0 text-secondary text-end">Don't remember password?<a href="forgot-password.php" target="blank" class="link-primary text-decoration-none">Forgate Password</a></p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</body>
</html>