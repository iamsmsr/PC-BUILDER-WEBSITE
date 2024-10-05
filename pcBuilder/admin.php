<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); 
    exit();
}

include 'dbConnect.php'; 
include 'header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_component'])) {
    $component_type = $_POST['component_type'];
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $power_consumption = intval($_POST['power_consumption']);
    $quantity = intval($_POST['quantity']);

    $sql = "INSERT INTO $component_type (name, price, power_consumption, quantity) VALUES ('$name', '$price', '$power_consumption', '$quantity')";
    if (mysqli_query($conn, $sql)) {
        echo "<p>New $component_type added successfully.</p>";
    } else {
        echo "<p>Error adding new component: " . mysqli_error($conn) . "</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $component_type = $_POST['component_type'];
    $id = intval($_POST['id']);
    $new_quantity = intval($_POST['quantity']);

    $sql = "UPDATE $component_type SET quantity = '$new_quantity' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<p>Quantity updated successfully.</p>";
    } else {
        echo "<p>Error updating quantity: " . mysqli_error($conn) . "</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_component'])) {
    $component_type = $_POST['component_type'];
    $id = intval($_POST['id']);

    $sql = "DELETE FROM $component_type WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<p>Component deleted successfully.</p>";
    } else {
        echo "<p>Error deleting component: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: row;
        }

        .left, .right {
            flex: 1;
            padding: 20px;
        }

        .left {
            border-right: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<h1>Admin Control Panel</h1>

<div class="container">
    <div class="left">
        <h2>Add a New Component</h2>
        <form method="post" action="">
            <label for="component_type">Component Type:</label>
            <select name="component_type" id="component_type">
                <option value="cpu">CPU</option>
                <option value="memory">Memory</option>
                <option value="gpu">GPU</option> 
            </select><br>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="price">Price:</label>
            <input type="text" name="price" id="price" required><br>

            <label for="power_consumption">Power Consumption:</label>
            <input type="text" name="power_consumption" id="power_consumption" required><br>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" required><br>

            <input type="submit" name="add_component" value="Add Component">
        </form>

        <h2>Update Component Quantity</h2>
        <form method="post" action="">
            <label for="component_type">Component Type:</label>
            <select name="component_type" id="component_type">
                <option value="cpu">CPU</option>
                <option value="memory">Memory</option>
                <option value="gpu">GPU</option> 
            </select><br>

            <label for="id">Component ID:</label>
            <input type="number" name="id" id="id" required><br>

            <label for="quantity">New Quantity:</label>
            <input type="number" name="quantity" id="quantity" required><br>

            <input type="submit" name="update_quantity" value="Update Quantity">
        </form>

        <h2>Delete a Component</h2>
        <form method="post" action="">
            <label for="component_type">Component Type:</label>
            <select name="component_type" id="component_type">
                <option value="cpu">CPU</option>
                <option value="memory">Memory</option>
                <option value="gpu">GPU</option> 
            </select><br>

            <label for="id">Component ID:</label>
            <input type="number" name="id" id="id" required><br>

            <input type="submit" name="delete_component" value="Delete Component">
        </form>
    </div>

    <div class="right">
        <h2>Current Inventory</h2>
        <?php
        foreach (['cpu', 'memory', 'gpu'] as $component_type) { 
            echo "<h3>" . ucfirst($component_type) . "</h3>";
            $sql = "SELECT * FROM $component_type";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Power Consumption</th>
                            <th>Quantity</th>
                        </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['price']) . "</td>
                            <td>" . htmlspecialchars($row['power_consumption']) . "</td>
                            <td>" . htmlspecialchars($row['quantity']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No $component_type components available.</p>";
            }
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
