<?php
session_start();
include 'dbConnect.php'; 
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        
        $sql = "SELECT id, password FROM people WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            
            if (password_verify($password, $row['password'])) {
            
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;

                
                header("Location: customer.php");
                exit();
            } else {
                echo "<p>Incorrect password.</p>";
            }
        } else {
            echo "<p>Username not found.</p>";
        }
    } else {
        echo "<p>All fields are required.</p>";
    }
}

mysqli_close($conn);
?>

<div class="form-container">
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>

<?php

include 'footer.php';
    ?>
