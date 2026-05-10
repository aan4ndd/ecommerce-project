<?php
$login = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql    = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id']  = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        header("location: index.php");
        exit();
    } else {
        $showError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – HaggleHub</title>
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

        .auth-wrap {
            flex: 1; display: flex; min-height: 100vh;
        }

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

        .submit-btn {
            width: 100%; padding: 13px;
            background: var(--blue); color: #fff;
            font-family: "Barlow Semi Condensed", sans-serif;
            font-size: 16px; font-weight: 700; border: none;
            border-radius: var(--radius); cursor: pointer;
            transition: opacity 0.2s; margin-top: 4px; margin-bottom: 16px;
        }
        .submit-btn:hover { opacity: 0.88; }

        .auth-switch { text-align: center; font-size: 14px; color: var(--muted); }
        .auth-switch a { color: var(--blue); font-weight: 600; }

        .alert-error {
            background: #fef0f0; border: 1px solid #f5c6cb; color: #a32d2d;
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

        .features { margin-top: 40px; display: flex; flex-direction: column; gap: 16px; width: 100%; max-width: 300px; }
        .feature-item {
            background: rgba(255,255,255,0.12); border-radius: var(--radius);
            padding: 14px 18px; display: flex; align-items: center; gap: 14px;
        }
        .feature-item .icon { font-size: 22px; }
        .feature-item .text { font-size: 14px; color: rgba(255,255,255,0.9); }
        .feature-item .text strong { display: block; font-size: 15px; color: #fff; margin-bottom: 2px; }

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
            <div class="auth-title">Welcome back</div>
            <div class="auth-sub">Login to continue shopping and bargaining.</div>

            <?php if ($showError): ?>
            <div class="alert-error">❌ Incorrect username or password.</div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>
                <button class="submit-btn" type="submit">Login →</button>
            </form>

            <div class="auth-switch">Don't have an account? <a href="register.php">Sign up</a></div>
        </div>
    </div>

    <!-- RIGHT: GRAPHIC -->
    <div class="auth-right">
        <div class="big-logo"><span>H</span>aggleHub</div>
        <h2>Your price, your rules.</h2>
        <p>Browse thousands of products and negotiate the best deal directly with sellers.</p>

        <div class="features">
            <div class="feature-item">
                <div class="icon">🤝</div>
                <div class="text"><strong>Bargain System</strong>Make offers on any product</div>
            </div>
            <div class="feature-item">
                <div class="icon">⚡</div>
                <div class="text"><strong>Fast Delivery</strong>Free delivery in just 4 days</div>
            </div>
            <div class="feature-item">
                <div class="icon">🔒</div>
                <div class="text"><strong>Secure & Safe</strong>Your data is always protected</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>