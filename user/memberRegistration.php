<?php
require "../_base.php";

if(is_post()){
    $name = req('name');
    $email = req('email');
    $password = req('password');
    $confirm = req('confirm');
    $gender = req('gender');
    $phone = req('phone');
    $f = get_file('photo');
    
    if(!$name){
        $_err["name"] = "Required";
    }
    else if(strlen($name) > 100){
        $_err["name"] = "Maximum 100 characters only";
    }

    if(!$email){
        $_err["email"] = "Required";
    }
    else if(strlen($email) > 100){
        $_err["email"] = "Maximum 100 characters only";
    }
    else if(!is_email($email)){
        $_err["email"] = "Invalid email";
    }
    else if(!is_unique($email,"user", "email")){
        $_err["email"] = "Duplicate";
    }

    if(!$password){
        $_err["password"] = "Required";
    }
    else if(strlen($password) < 5 || strlen($password) > 50){
        $_err["password"] = "Between 5-50 character";
    }
    
    if(!$confirm){
        $_err["confirm"] = "Required";
    }
    else if($confirm != $password){
        $_err["confirm"] = "Passwords do not match";
    }

    if(!$gender){
        $_err["gender"] = "Required";
    }

    if(!$phone){
        $_err["phone"] = "Required";
    }
    else if(strlen($phone) < 8 || strlen($phone) > 10){
        $_err["phone"] = "Between 8-10 numbers";
    }

    if(!$f){
        $_err["photo"] = "Required";
    }
    else if(!str_starts_with($f->type, "image/")){
        $_err["photo"] = "Must be image";
    }
    else if($f->size > 1 * 1024 * 1024){
        $_err["photo"] = "Maximum size is 1MB";
    }

    if (!$_err) {
        $photo = save_photo($f, '../photos');
        $token = bin2hex(random_bytes(32));

        
        $stm = $_db->prepare("INSERT INTO user (name, email, password, gender, phone, photo, role, verification_token, is_verified) 
                            VALUES (?, ?, SHA1(?), ?, ?, ?, 'Member', ?, 0)");
        $stm->execute([$name, $email, $password, $gender, $phone, $photo, $token]);
        
        $verify_link = "/user/verify.php?token=$token";

        echo "<h3>Account Created!</h3>";
        echo "<p>Please verify your email:</p>";
        echo "<a href='$verify_link'>$verify_link</a>";

        exit();
    }
}

$_title = "Registration";
include "../_head.php";
?>

<form action="memberRegistration.php" method="post" enctype="multipart/form-data" class="registration-form">
    <label for="name">name</label>
    <?= html_text("name") ?>
    <?= err("name") ?>

    <label for="email">email</label>
    <?= html_text("email") ?>
    <?= err("email") ?>

    <label for="password">password</label>
    <?= html_password("password") ?>
    <?= err("password") ?>

    <label for="confirm">confirm password</label>
    <?= html_password("confirm") ?>
    <?= err("confirm") ?>

    <label>gender</label>
    <?= html_radios("gender", $_genders), err("gender") ?>

    <label for="phone">phone</label>
    <?= html_text("phone") ?>
    <?= err("phone") ?>

    <label for="photo">photo</label>
    <label class="upload" tabindex="0">
    <?= html_file("photo", "image/", "hidden") ?>
    <img src="/images/photo.jpg" alt="placeholder">
    </label>
    <?= err("photo") ?>    

    <section>
        <button type="submit">Create Account</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include "../_foot.php";