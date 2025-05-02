<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log In</title>
  <link rel="stylesheet" href="./CSS/log-in-style.css" />
  <link rel="stylesheet" href="./CSS/global-styling.css">
</head>
<body>
  <header>
    <a href="./citizen-form.html" class="logo">CITYNA</a>
    <div class="buttons">
      <a href="./log-in.php" class="btn primaryBtn">Log in</a>
      <a href="./sign-up.html" class="btn secondaryBtn">Sign Up</a>
    </div>
  </header>

  <main>
    <div class="form-container">
      <h1>Log In</h1>
      <!-- this php code is for the error is the email or the pass is mistyped -->
      <?php if (!empty($_SESSION['login_error'])): ?>
      <div class="error-box">
      <?php
      echo $_SESSION['login_error'];
      unset($_SESSION['login_error']);
      ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="handle-login.php">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required />

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required />

        <button type="submit" class="btn primaryBtn submit">Log In</button>

        <div class="extra-links">
          <a href="./forgot-password.html">Forgot password?</a>
          <a href="./sign-up.html">Don’t have an account? Go to Sign up</a>
        </div>
      </form>
    </div>
    <div>
      <img src="./Pictures/log-in.png" alt="">
    </div>
  </main>
</body>
</html>
