<?php
session_start();
include "connection.php"; // DB connection

$error = ""; // store any error messages

if (isset($_POST['login'])) {
    $emailInput = $_POST['email'];
    $passwordInput = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $emailInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($passwordInput, $row['password'])) {
            // Login successful
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: home.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Wrong email or password!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">

            <!-- Display error messages -->
            <?php if (!empty($error)) : ?>
                <div class="message">
                    <p><?php echo $error; ?></p>
                </div>
                <br>
                <a href='login.php'><button class='btn'>Go Back</button></a>
            <?php endif; ?>

            <header>Login</header>
            <hr>
            <form action="#" method="POST">
                <div class="form-box">

                    <div class="input-container">
                        <i class="fa fa-envelope icon"></i>
                        <input class="input-field" type="email" placeholder="Email Address" name="email" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field password" type="password" placeholder="Password" name="password" required>
                        <i class="fa fa-eye toggle icon"></i>
                    </div>

                    <div class="remember">
                        <input type="checkbox" class="check" name="remember_me">
                        <label for="remember">Remember me</label>
                        <span><a href="forgot.php">Forgot password</a></span>
                    </div>

                </div>

                <input type="submit" name="login" id="submit" value="Login" class="button">

                <div class="links">
                    Don't have an account? <a href="signup.php">Signup Now</a>
                </div>
            </form>
        </div>
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
        });
    </script>
</body>

</html>