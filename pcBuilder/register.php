<?php
session_start();
include 'dbConnect.php'; 
include 'header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        
        $sql_check = "SELECT id FROM people WHERE username = '$username'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "<p>Username already exists. Please choose a different username.</p>";
        } else {
    
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $sql = "INSERT INTO people (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if (mysqli_query($conn, $sql)) {
            
                header("Location: login.php");
                exit();
            } else {
                echo "<p>Error: " . mysqli_error($conn) . "</p>";
            }
        }
    } else {
        echo "<p>All fields are required.</p>";
    }
}

mysqli_close($conn);
?>

<div class="form-container">
    <h2>Register</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Register">
    </form>
</div>


</body>


<?php include 'footer.php'; // Include the footer ?>
