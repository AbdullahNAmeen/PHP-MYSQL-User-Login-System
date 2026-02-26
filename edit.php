<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

$id = $_SESSION['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user securely using prepared statements
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $hashedPassword, $id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile. Please try again!";
    }

    $stmt->close();
}

// Fetch current user data for form pre-fill
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="form-box box">

    <?php if (!empty($error)) : ?>
      <div class="message"><p><?php echo htmlspecialchars($error); ?></p></div><br>
    <?php elseif (!empty($success)) : ?>
      <div class="message"><p><?php echo htmlspecialchars($success); ?></p></div><br>
    <?php endif; ?>

    <header>Change Profile</header>
    <form action="#" method="POST">

      <div class="form-box">
        <div class="input-container">
          <i class="fa fa-user icon"></i>
          <input class="input-field" type="text" placeholder="Username" name="username"
                 value="<?php echo htmlspecialchars($res_username); ?>" required>
        </div>

        <div class="input-container">
          <i class="fa fa-envelope icon"></i>
          <input class="input-field" type="email" placeholder="Email Address" name="email"
                 value="<?php echo htmlspecialchars($res_email); ?>" required>
        </div>

        <div class="input-container">
          <i class="fa fa-lock icon"></i>
          <input class="input-field password" type="password" placeholder="Password" name="password" required>
          <i class="fa fa-eye toggle icon"></i>
        </div>
      </div>

      <div class="field">
        <input type="submit" name="update" id="submit" value="Update" class="btn">
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