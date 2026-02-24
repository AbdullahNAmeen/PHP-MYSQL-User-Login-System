<?php
session_start();
include "connection.php";

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$error = "";
$success = "";

// Handle profile update
if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement to update user info
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $hashedPassword, $id);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $_SESSION['username'] = $username; // Update session username
        } else {
            $error = "Error updating profile. Please try again.";
        }

        $stmt->close();
    }
}

// Fetch current user info for the form
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($res_username, $res_email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">

            <header>Change Profile</header>
            <hr>

            <!-- Display messages -->
            <?php if (!empty($error)) : ?>
                <div class="message"><p><?php echo $error; ?></p></div><br>
            <?php elseif (!empty($success)) : ?>
                <div class="message"><p><?php echo $success; ?></p></div><br>
                <a href='home.php'><button class='btn'>Go Home</button></a>
            <?php endif; ?>

            <?php if (empty($success)) : ?>
                <form action="#" method="POST">
                    <div class="input-container">
                        <i class="fa fa-user icon"></i>
                        <input class="input-field" type="text" name="username" value="<?php echo htmlspecialchars($res_username); ?>" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-envelope icon"></i>
                        <input class="input-field" type="email" name="email" value="<?php echo htmlspecialchars($res_email); ?>" required>
                    </div>

                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input class="input-field password" type="password" name="password" placeholder="New Password" required>
                        <i class="fa fa-eye toggle icon"></i>
                    </div>

                    <center>
                        <input type="submit" name="update" value="Update Profile" class="btn">
                    </center>
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