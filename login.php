<?php
include '_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');
    $password = req('password');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Login user
    if (!$_err) {
        $stm = $_db->prepare('
            SELECT * FROM user
            WHERE email = ? AND password = SHA1(?)
        ');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            temp('info', 'Login successfully');

            // Remember email and password in cookies if checkbox is checked
            if (post('remember')) {
                setcookie('login_email', $email, time() + (86400 * 30), '/');      // 30 days
                setcookie('login_password', $password, time() + (86400 * 30), '/'); // 30 days
            } else {
                // Remove cookies if checkbox not checked
                setcookie('login_email', '', time() - 3600, '/');
                setcookie('login_password', '', time() - 3600, '/');
            }

            login($u);
        }

        else {
            $_err['password'] = 'Not matched';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';
include '_head.php';
?>

<p class="top-form-gap"></p>

<form method="post" class="form">
    <label for="email">Email</label>
    <input type="text" id="email" name="email" 
           value="<?= encode($_POST['email'] ?? $_COOKIE['login_email'] ?? '') ?>" 
           maxlength="100">
    <?= err('email') ?>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" 
           value="<?= encode($_POST['password'] ?? $_COOKIE['login_password'] ?? '') ?>" 
           maxlength="100">
    <?= err('password') ?>

    <label>
        <input type="checkbox" name="remember" value="1"
            <?= isset($_COOKIE['login_email']) ? 'checked' : '' ?>>
        Remember me
    </label>

    <section>
        <button>Login</button>
        <button type="reset">Reset</button>
    </section>
</form>

<?php
include '_foot.php';