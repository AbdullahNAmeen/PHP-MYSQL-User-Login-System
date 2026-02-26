<?php
session_start();
require_once "connection.php"; // secure DB connection

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpass'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "This email is already used. Try another one!";
    } else {
        if ($password === $cpassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($insertStmt->execute()) {
                $success = "You have registered successfully!";
            } else {
                $error = "Error registering user. Please try again!";
            }

            $insertStmt->close();
        } else {
            $error = "Passwords do not match!";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/style1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container">
  <div class="form-box box">

    <?php if (!empty($error)) : ?>
      <div class="message"><p><?php echo htmlspecialchars($error); ?></p></div>
      <br><a href="signup.php"><button class="btn">Go Back</button></a>
    <?php elseif (!empty($success)) : ?>
      <div class="message"><p><?php echo htmlspecialchars($success); ?></p></div>
      <br><a href="login.php"><button class="btn">Login Now</button></a>
    <?php endif; ?>

    <header>Sign Up</header>
    <hr>

    <form action="#" method="POST">
      <div class="form-box">
        <div class="input-container">
          <i class="fa fa-user icon"></i>
          <input class="input-field" type="text" placeholder="Username" name="username" required>
        </div>

        <div class="input-container">
          <i class="fa fa-envelope icon"></i>
          <input class="input-field" type="email" placeholder="Email Address" name="email" required>
        </div>

        <div class="input-container">
          <i class="fa fa-lock icon"></i>
          <input class="input-field password" type="password" placeholder="Password" name="password" required>
          <i class="fa fa-eye toggle icon"></i>
        </div>

        <div class="input-container">
          <i class="fa fa-lock icon"></i>
          <input class="input-field password" type="password" placeholder="Confirm Password" name="cpass" required>
          <i class="fa fa-eye icon"></i>
        </div>
      </div>

      <center><input type="submit" name="register" id="submit" value="Signup" class="btn"></center>

      <div class="links">
        Already have an account? <a href="login.php">Signin Now</a>
      </div>
    </form>
  </div>
</div>

<script>
  const toggle = document.querySelector(".toggle"),
        inputs = document.querySelectorAll(".password");

  toggle.addEventListener("click", () => {
    inputs.forEach(input => {
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
      }
    });
  });
</script>
</body>
</html>