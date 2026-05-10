<?php
$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';
    $username  = $_POST["username"];
    $password  = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    $existSql  = "SELECT * FROM users WHERE username='$username'";
    $result    = mysqli_query($conn, $existSql);

    if (mysqli_num_rows($result) > 0) {
        $showError = "Username already exists.";
    } else {
        if ($password == $cpassword) {
            $sql    = "INSERT INTO users (username, password, dt) VALUES ('$username', '$password', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
                header("location: login.php");
                exit();
            }
        } else {
            $showError = "Passwords do not match.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – HaggleHub</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        :root {
            --blue: rgb(24, 57, 243);
            --blue-light: rgba(24, 57, 243, 0.08);
            --border: #e0e0e0;
            --radius: 12px;
            --muted: #777;
        }

        body { min-height: 100vh; display: flex; flex-direction: column; background: whitesmoke; }

        .auth-wrap { flex: 1; display: flex; min-height: 100vh; }

        /* LEFT PANEL */
        .auth-left {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 60px 48px; background: #fff;
        }
        .auth-box { width: 100%; max-width: 380px; }

        .auth-logo { font-size: 28px; font-weight: 700; margin-bottom: 32px; }
        .auth-logo span { color: var(--blue); }

        .auth-title { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
        .auth-sub   { font-size: 14px; color: var(--muted); margin-bottom: 32px; }

        .field { margin-bottom: 18px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
        .field input {
            width: 100%; padding: 11px 14px;
            border: 1px solid var(--border); border-radius: var(--radius);
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 15px; outline: none; transition: border-color 0.2s;
            background: #fafafa; box-sizing: border-box;
        }
        .field input:focus { border-color: var(--blue); background: #fff; }

        .password-hint { font-size: 12px; color: var(--muted); margin-top: 5px; }

        .submit-btn {
            width: 100%; padding: 13px;
            background: var(--blue); color: #fff;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 16px; font-weight: 700; border: none;
            border-radius: var(--radius); cursor: pointer;
            transition: opacity 0.2s; margin-top: 4px; margin-bottom: 16px;
        }
        .submit-btn:hover { opacity: 0.88; }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        .auth-switch { text-align: center; font-size: 14px; color: var(--muted); }
        .auth-switch a { color: var(--blue); font-weight: 600; }

        .alert-error {
            background: #fef0f0; border: 1px solid #f5c6cb; color: #a32d2d;
            padding: 11px 14px; border-radius: var(--radius);
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success {
            background: #edfbf2; border: 1px solid #a3d9b1; color: #1a6b38;
            padding: 11px 14px; border-radius: var(--radius);
            font-size: 13px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        /* RIGHT PANEL */
        .auth-right {
            flex: 1; background: var(--blue);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 60px 48px; color: #fff;
        }
        .auth-right .big-logo { font-size: 52px; font-weight: 800; margin-bottom: 16px; letter-spacing: -1px; }
        .auth-right .big-logo span { color: rgba(255,255,255,0.5); }
        .auth-right h2 { font-size: 24px; font-weight: 600; margin-bottom: 12px; text-align: center; }
        .auth-right p  { font-size: 15px; color: rgba(255,255,255,0.75); text-align: center; max-width: 300px; line-height: 1.7; }

        .steps { margin-top: 40px; display: flex; flex-direction: column; gap: 0; width: 100%; max-width: 300px; }
        .step {
            display: flex; align-items: flex-start; gap: 16px;
            padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,0.2); display: flex; align-items: center;
            justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .step-text { font-size: 14px; color: rgba(255,255,255,0.85); line-height: 1.5; }
        .step-text strong { display: block; color: #fff; font-size: 15px; margin-bottom: 2px; }

        @media (max-width: 700px) {
            .auth-right { display: none; }
            .auth-left  { padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    <!-- LEFT: FORM -->
    <div class="auth-left">
        <div class="auth-box">
            <div class="auth-logo"><span>H</span>aggleHub</div>
            <div class="auth-title">Create an account</div>
            <div class="auth-sub">Join HaggleHub and start bargaining today.</div>

            <?php if ($showAlert): ?>
            <div class="alert-success">✅ Account created! Redirecting to login…</div>
            <?php endif; ?>

            <?php if ($showError): ?>
            <div class="alert-error">❌ <?php echo $showError; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required autocomplete="username">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required autocomplete="new-password">
                </div>
                <div class="field">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" id="cpassword" name="cpassword" placeholder="Repeat your password" required autocomplete="new-password">
                    <div class="password-hint">Must match the password above.</div>
                </div>
                <button class="submit-btn" type="submit" onclick="this.disabled=true; this.form.submit();">Create Account →</button>
            </form>

            <div class="auth-switch">Already have an account? <a href="login.php">Login</a></div>
        </div>
    </div>

    <!-- RIGHT: GRAPHIC -->
    <div class="auth-right">
        <div class="big-logo"><span>H</span>aggleHub</div>
        <h2>Get started in seconds.</h2>
        <p>Create your free account and start making offers on products you love.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Create your account</strong>Pick a username and password</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Browse products</strong>Explore our full catalogue</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Make your offer</strong>Bargain and get the best price</div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-text"><strong>Checkout &amp; enjoy</strong>Fast, free delivery to your door</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>