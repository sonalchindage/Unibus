<?php
include 'includes/config.php';
include 'includes/enc.php';
if(isset($_GET['id'])){
    $id = decrypt($_GET['id']);
    $emailValidate = 1;
    $query = "UPDATE `userregistration` SET emailValidate = $emailValidate WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id); // 'ss' specifies that both variables are strings
    if($stmt->execute()){
        // echo "good";
        echo "<script>window.location.href='newLogin.php';</script>";
                
    }else{
        echo "Request bad";
    }
}else{
    echo 'wrong';
}
?>