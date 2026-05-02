<?php
include '../_base.php';

if (is_post()) {
    $email    = req('email');
    $password = req('password');

    // ----------------------VALIDATION-------------------------------------------------------
    if ($email == '') {
        $_err['email'] = 'Required';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    if ($password == '') {
        $_err['password'] = 'Required';
    }
    // ----------------------VALIDATION-------------------------------------------------------

    if (!$_err) {
        // Check login attempts (attempt blocking)
        $stm = $_db->prepare('SELECT * FROM login_attempts WHERE email = ?');
        $stm->execute([$email]);
        $attempt = $stm->fetch();

        if ($attempt && $attempt->blocked_until && strtotime($attempt->blocked_until) > time()) {
            $_err['password'] = 'Account is temporarily blocked. Try again later.';
        }
    }

    if (!$_err) {
        // Check if user exists and credentials match
        $stm = $_db->prepare('SELECT * FROM user WHERE email = ? AND password = SHA1(?)');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            // Separate check: Is the account status blocked?
            if ($u->status === 'Blocked') {
                // If the account is blocked, show an error message and prevent login
                $_err['password'] = 'Your account has been blocked. Please contact support.';
            } else {                                                              
                $stm = $_db->prepare('DELETE FROM login_attempts WHERE email = ?');
                $stm->execute([$email]);                                 //if account == active, proceed with login
                temp('info', 'Login successful');
                login($u);
            }
        } else {                                                             // Invalid login attempt

            $_err['password'] = 'Email or password is incorrect.';

            if ($attempt) {                                                   // 失败登录增加
                $new_attempts = $attempt->attempts + 1;

                if ($new_attempts >= 3) {                                   // permanently block the account 15 minute if fail to login 3 times
                    $blocked_until = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                    $stm = $_db->prepare('UPDATE login_attempts SET attempts = ?, blocked_until = ? WHERE email = ?');
                    $stm->execute([$new_attempts, $blocked_until, $email]);
                    $_err['password'] = 'Account is temporarily blocked due to multiple failed attempts. Try again after 15 minutes.';
                } else {                                                    // 更新失败次数
                    $stm = $_db->prepare('UPDATE login_attempts SET attempts = ? WHERE email = ?');
                    $stm->execute([$new_attempts, $email]);
                }
            } else {
                // insert first failed login attempt
                $stm = $_db->prepare('INSERT INTO login_attempts (email, attempts) VALUES (?, 1)');
                $stm->execute([$email]);
            }
        }
    }
}

include '../_head.php';
?>

<form method="post" class="form">
    <p><span id="header">USER LOGIN</span></p>
    
    <label for="email">EMAIL</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="password">PASSWORD</label>
    <div class="password-wrapper">
        <?= html_password('password', 'maxlength="100" id="password" class="password-input"') ?>
        <img src="/images/eyeclose.png" class="toggle-password" onclick="togglePassword('password', this)" alt="Show/Hide Password">
    </div>
    <?= err('password') ?>

    <section>
        <button>LOGIN</button>
        <button type="reset">RESET</button>
    </section>
    <p><span id="forgotpassword"> <a href="/user/reset.php"> Forgot password?</a></span></p>
    <p><span id="newtoshopping"> New to Shopping?<a href="/user/register.php"> Register</a></span></p>
</form>

<script>
    function togglePassword(id, toggleIcon) {
        var input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            toggleIcon.src = "/images/eyeopen.png";
        } else {
            input.type = "password";
            toggleIcon.src = "/images/eyeclose.png";
        }
    }
</script>

<style>
    .password-wrapper {
        position: relative;
    }
    .password-input {
        width: 98%;
    }
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        width: 20px;
        height: 20px;
    }
</style>    

<?php
include '../_foot.php';
?>
