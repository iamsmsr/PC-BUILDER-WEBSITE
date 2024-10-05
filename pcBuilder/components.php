<?php
session_start();

include 'dbConnect.php';
include 'header.php';

$component_type = $_GET['type'];

$valid_types = ['cpu', 'memory', 'gpu'];
if (!in_array($component_type, $valid_types)) {
    die("Invalid component type.");
}

$sql = "SELECT * FROM $component_type";
$result = mysqli_query($conn, $sql);
?>

<h1>Select <?php echo ucfirst($component_type); ?></h1>

<?php if (mysqli_num_rows($result) > 0): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Power Consumption</th>
            <th>Quantity</th>
            <th>Select</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <form action="index.php" method="get">
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['power_consumption']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($component_type); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
                    <button type="submit" name="selected_item" value="<?php echo htmlspecialchars($row['name']); ?>">Select</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No <?php echo $component_type; ?> found.</p>
<?php endif; ?>

<?php
mysqli_close($conn);
include 'footer.php';
?>
