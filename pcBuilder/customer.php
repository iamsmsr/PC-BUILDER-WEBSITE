<?php
session_start();
include 'dbConnect.php'; 
include 'header.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php");
    exit();
}


$selected_cpu = isset($_SESSION['cpu']) ? $_SESSION['cpu'] : 'No CPU Selected';
$selected_memory = isset($_SESSION['memory']) ? $_SESSION['memory'] : 'No Memory Selected';
$selected_gpu = isset($_SESSION['gpu']) ? $_SESSION['gpu'] : 'No GPU Selected'; 

$cpu_quantity = isset($_SESSION['cpu_quantity']) ? intval($_SESSION['cpu_quantity']) : 1;
$memory_quantity = isset($_SESSION['memory_quantity']) ? intval($_SESSION['memory_quantity']) : 1;
$gpu_quantity = isset($_SESSION['gpu_quantity']) ? intval($_SESSION['gpu_quantity']) : 1;

$cpu_total_price = isset($_SESSION['cpu_price']) ? $cpu_quantity * floatval($_SESSION['cpu_price']) : 0;
$memory_total_price = isset($_SESSION['memory_price']) ? $memory_quantity * floatval($_SESSION['memory_price']) : 0;
$gpu_total_price = isset($_SESSION['gpu_price']) ? $gpu_quantity * floatval($_SESSION['gpu_price']) : 0;

$overall_total_price = $cpu_total_price + $memory_total_price + $gpu_total_price;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {

    $order_description = "CPU: $selected_cpu, Memory: $selected_memory, GPU: $selected_gpu";
    $comment = "Waiting for approval";
    $customer_id = $_SESSION['user_id'];


    $sql = "INSERT INTO orders (customer_id, order_description, total, comment) 
            VALUES ('$customer_id', '$order_description', '$overall_total_price', '$comment')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['order_message'] = "Your order has been placed! Waiting for approval.";
        
    
        unset($_SESSION['cpu'], $_SESSION['memory'], $_SESSION['gpu']);
        unset($_SESSION['cpu_quantity'], $_SESSION['memory_quantity'], $_SESSION['gpu_quantity']);
        unset($_SESSION['cpu_price'], $_SESSION['memory_price'], $_SESSION['gpu_price']);
        
        header("Location: customer.php");
        exit(); 
    } else {
        echo "<p>Error: Unable to place order. Please try again.</p>";
    }
}

?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<div class="order-summary">
    <h3>Your Selected Items: <?php echo date("   l jS \of F Y h:i:s A");?></h3>
    <ul>
        <li>CPU: <?php echo htmlspecialchars($selected_cpu); ?> - $<?php echo $cpu_total_price; ?></li>
        <li>Memory: <?php echo htmlspecialchars($selected_memory); ?> - $<?php echo $memory_total_price; ?></li>
        <li>GPU: <?php echo htmlspecialchars($selected_gpu); ?> - $<?php echo $gpu_total_price; ?></li>
    </ul>
    <p><strong>Total Price: $<?php echo $overall_total_price; ?></strong></p>
</div>

<form method="post" action="">
    <input type="submit" name="place_order" value="Place Order">
    <input type="submit" name="logout" value="Logout">
</form>

<?php

if (isset($_SESSION['order_message'])) {
    echo "<p>" . htmlspecialchars($_SESSION['order_message']) . "</p>";
    unset($_SESSION['order_message']); 
}

include 'footer.php';
mysqli_close($conn); 
?>
