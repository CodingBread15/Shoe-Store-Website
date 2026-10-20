<?php
require "../_base.php";

$token = $_GET['token'] ?? '';

if(!$token){
    echo "Invalid request";
    exit();
}

$stm = $_db->prepare("SELECT * FROM user WHERE verification_token = ?");
$stm->execute([$token]);
$user = $stm->fetch();

if ($user) {

    //verified
    $stm = $_db->prepare("
        UPDATE user 
        SET is_verified = 1, verification_token = NULL 
        WHERE verification_token = ?
    ");
    $stm->execute([$token]);

    echo "<h2>✅ Email Verified Successfully!</h2>";
    echo "<a href='../login.php'>Go Login</a>";

} else {
    echo "<h2>❌ Invalid or expired token</h2>";
}