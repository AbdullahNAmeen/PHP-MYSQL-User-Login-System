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

$error = ""; // store any error messages
$success = ""; // store success messages

if (isset($_POST['register'])) {
    $usernameInput   = trim($_POST['username']);
    $emailInput      = trim($_POST['email']);
    $passwordInput   = $_POST['password'];
    $confirmPassword = $_POST['cpass'];

    // Check if passwords match
    if ($passwordInput !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists using prepared statement
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $emailInput);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This email is already used. Try another one please!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

            // Insert new user using prepared statement
            $insertStmt = $conn->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $usernameInput, $emailInput, $hashedPassword);
            if ($insertStmt->execute()) {
                $success = "You have registered successfully!";
            } else {
                $error = "There was an error. Please try again!";
            }
            $insertStmt->close();
        }
        $stmt->close();
    }
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

            <header>Sign Up</header>
            <hr>

            <!-- Display success/error messages -->
            <?php if (!empty($error)) : ?>
                <div class="message"><p><?php echo $error; ?></p></div><br>
                <a href='signup.php'><button class='btn'>Go Back</button></a>
            <?php elseif (!empty($success)) : ?>
                <div class="message"><p><?php echo $success; ?></p></div><br>
                <a href='login.php'><button class='btn'>Login Now</button></a>
            <?php endif; ?>

            <?php if (empty($success)) : ?>
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
                            <i class="fa fa-eye icon toggle"></i>
                        </div>

                        <div class="input-container">
                            <i class="fa fa-lock icon"></i>
                            <input class="input-field password" type="password" placeholder="Confirm Password" name="cpass" required>
                            <i class="fa fa-eye icon toggle"></i>
                        </div>

                        <center>
                            <input type="submit" name="register" id="submit" value="Signup" class="btn">
                        </center>

                        <div class="links">
                            Already have an account? <a href="login.php">Signin Now</a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <script>
        const toggles = document.querySelectorAll(".toggle");
        toggles.forEach(toggle => {
            const input = toggle.previousElementSibling;
            toggle.addEventListener("click", () => {
                if (input.type === "password") {
                    input.type = "text";
                    toggle.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    toggle.classList.replace("fa-eye-slash", "fa-eye");
                }
            });
        });
    </script>
</body>
</html>