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


      <header>Sign Up</header>
      <hr>

      <form action="#" method="POST">


        <div class="form-box">

          <?php
session_start();
include "connection.php";

// Check if the signup form was submitted
if (isset($_POST['register'])) {

    // Get input values from the form
    $usernameInput    = $_POST['username'];
    $emailInput       = $_POST['email'];
    $passwordInput    = $_POST['password'];
    $confirmPassword  = $_POST['cpass'];

    // Check if the email already exists in the database
    $checkQuery = "SELECT * FROM users WHERE email='{$emailInput}'";
    $checkResult = mysqli_query($conn, $checkQuery);

    // Hash the password before saving
    $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

    // Generate a random key (not used yet, but left from original code)
    $key = bin2hex(random_bytes(12));

    if (mysqli_num_rows($checkResult) > 0) {
        // Email already exists
        echo "<div class='message'>
                <p>This email is already used. Try another one please!</p>
              </div><br>";
        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";

    } else {

        if ($passwordInput === $confirmPassword) {
            // Insert new user into database
            $insertQuery = "INSERT INTO users(username,email,password) VALUES('$usernameInput','$emailInput','$hashedPassword')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                // Successful registration
                echo "<div class='message'>
                        <p>You have registered successfully!</p>
                      </div><br>";
                echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
            } else {
                // Failed to insert (probably rare)
                echo "<div class='message'>
                        <p>There was an error. Please try again!</p>
                      </div><br>";
                echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
            }

        } else {
            // Passwords do not match
            echo "<div class='message'>
                    <p>Passwords do not match.</p>
                  </div><br>";
            echo "<a href='signup.php'><button class='btn'>Go Back</button></a>";
        }
    }

} else {
    // Show the signup form if no POST submission
    ?>

    <div class="input-container">
        <!-- Username input -->
        <i class="fa fa-user icon"></i>
        <input class="input-field" type="text" placeholder="Username" name="username" required>
    </div>

    <div class="input-container">
        <!-- Email input -->
        <i class="fa fa-envelope icon"></i>
        <input class="input-field" type="email" placeholder="Email Address" name="email" required>
    </div>

    <div class="input-container">
        <!-- Password input -->
        <i class="fa fa-lock icon"></i>
        <input class="input-field password" type="password" placeholder="Password" name="password" required>
        <i class="fa fa-eye icon toggle"></i>
    </div>

    <div class="input-container">
        <!-- Confirm password input -->
        <i class="fa fa-lock icon"></i>
        <input class="input-field" type="password" placeholder="Confirm Password" name="cpass" required>
        <i class="fa fa-eye icon"></i>
    </div>

    <center>
        <input type="submit" name="register" id="submit" value="Signup" class="btn">
    </center>

    <div class="links">
        Already have an account? <a href="login.php">Signin Now</a>
    </div>

<?php
}
?>

  </div>

  <script>
    const toggle = document.querySelector(".toggle"),
      input = document.querySelector(".password");
    toggle.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
      }
    })
  </script>
</body>

</html>