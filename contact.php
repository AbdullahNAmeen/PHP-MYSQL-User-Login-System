<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>
    <div class="container">
        <div class="form-box box">

            <?php
            include "connection.php";

            if (isset($_POST['submit'])) {
    
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $subject = trim($_POST['subject']);
                $message = trim($_POST['message']);

                $stmt = $conn->prepare("INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $subject, $message);

            if ($stmt->execute()) {
            echo "<div class='message'>
                <p>Message sent successfully</p>
              </div><br>";
            echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
            } else {
            echo "<div class='message'>
                <p>Message sending failed</p>
              </div><br>";
            echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
            }

                $stmt->close();
            }
            ?>

        </div>
    </div>
</body>

</html>