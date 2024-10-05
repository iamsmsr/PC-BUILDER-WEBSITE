<?php
session_start(); 

include 'dbConnect.php';
include 'header.php';


if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_unset(); 
    header('Location: index.php'); 
    exit();
}


$selected_cpu = isset($_SESSION['cpu']) ? $_SESSION['cpu'] : 'No CPU Selected';
$selected_memory = isset($_SESSION['memory']) ? $_SESSION['memory'] : 'No Memory Selected';
$selected_gpu = isset($_SESSION['gpu']) ? $_SESSION['gpu'] : 'No GPU Selected';

$cpu_price = isset($_SESSION['cpu_price']) ? floatval($_SESSION['cpu_price']) : 0;
$memory_price = isset($_SESSION['memory_price']) ? floatval($_SESSION['memory_price']) : 0;
$gpu_price = isset($_SESSION['gpu_price']) ? floatval($_SESSION['gpu_price']) : 0;

$cpu_quantity = isset($_SESSION['cpu_quantity']) ? intval($_SESSION['cpu_quantity']) : 1;
$memory_quantity = isset($_SESSION['memory_quantity']) ? intval($_SESSION['memory_quantity']) : 1;
$gpu_quantity = isset($_SESSION['gpu_quantity']) ? intval($_SESSION['gpu_quantity']) : 1;


$cpu_total_price = $cpu_quantity * $cpu_price;
$memory_total_price = $memory_quantity * $memory_price;
$gpu_total_price = $gpu_quantity * $gpu_price;
$overall_total_price = $cpu_total_price + $memory_total_price + $gpu_total_price;


if (isset($_GET['quantity_change']) && isset($_GET['type'])) {
    $type = htmlspecialchars($_GET['type']);
    $change = intval($_GET['quantity_change']);

    if ($type === 'cpu') {
        $cpu_quantity += $change;
        if ($cpu_quantity < 1) $cpu_quantity = 1;
        $_SESSION['cpu_quantity'] = $cpu_quantity;
    } elseif ($type === 'memory') {
        $memory_quantity += $change;
        if ($memory_quantity < 1) $memory_quantity = 1;
        $_SESSION['memory_quantity'] = $memory_quantity;
    } elseif ($type === 'gpu') {
        $gpu_quantity += $change;
        if ($gpu_quantity < 1) $gpu_quantity = 1;
        $_SESSION['gpu_quantity'] = $gpu_quantity;
    }

    
    $cpu_total_price = $cpu_quantity * $cpu_price;
    $memory_total_price = $memory_quantity * $memory_price;
    $gpu_total_price = $gpu_quantity * $gpu_price;
    $overall_total_price = $cpu_total_price + $memory_total_price + $gpu_total_price;
}


if (isset($_GET['selected_item']) && isset($_GET['type']) && isset($_GET['price'])) {
    $selected_item_name = htmlspecialchars($_GET['selected_item']);
    $component_type = htmlspecialchars($_GET['type']);
    $item_price = floatval($_GET['price']);

    if ($component_type === 'cpu') {
        $_SESSION['cpu'] = $selected_item_name;
        $_SESSION['cpu_price'] = $item_price;
        $selected_cpu = $selected_item_name;
        $cpu_price = $item_price;
    } elseif ($component_type === 'memory') {
        $_SESSION['memory'] = $selected_item_name;
        $_SESSION['memory_price'] = $item_price;
        $selected_memory = $selected_item_name;
        $memory_price = $item_price;
    } elseif ($component_type === 'gpu') {
        $_SESSION['gpu'] = $selected_item_name;
        $_SESSION['gpu_price'] = $item_price;
        $selected_gpu = $selected_item_name;
        $gpu_price = $item_price;
    }

    $cpu_total_price = $cpu_quantity * $cpu_price;
    $memory_total_price = $memory_quantity * $memory_price;
    $gpu_total_price = $gpu_quantity * $gpu_price;
    $overall_total_price = $cpu_total_price + $memory_total_price + $gpu_total_price;
}
?>

<div class="total-price">
    <strong>Total Price: $<?php echo $overall_total_price; ?></strong>
    <div class="button-container">
        <a href="index.php?reset=true">
            <button class="reset-button">Reset</button>
        </a>
        <button class="print-button" onclick="window.print()">Print</button>
    </div>
</div>

<div class="row">
    <label for="cpu">CPU:</label>
    <div class="selected-item" id="cpu-selected">
        <?php echo $selected_cpu; ?>
    </div>
    <div class="price">
        <?php echo $cpu_price ? "Price: $$cpu_price" : ''; ?>
    </div>
    <div class="quantity-controls">
        <a href="index.php?type=cpu&quantity_change=-1" class="quantity-button">-</a>
        <span><?php echo $cpu_quantity; ?></span>
        <a href="index.php?type=cpu&quantity_change=1" class="quantity-button">+</a>
    </div>
    <div>Total Price: $<?php echo $cpu_total_price; ?></div>
    <form method="get" action="components.php">
        <input type="hidden" name="type" value="cpu">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($cpu_price); ?>">
        <button type="submit" name="action" value="select_cpu" class="select-button">Select CPU</button>
    </form>
</div>

<div class="row">
    <label for="memory">Memory:</label>
    <div class="selected-item" id="memory-selected">
        <?php echo $selected_memory; ?>
    </div>
    <div class="price">
        <?php echo $memory_price ? "Price: $$memory_price" : ''; ?>
    </div>
    <div class="quantity-controls">
        <a href="index.php?type=memory&quantity_change=-1" class="quantity-button">-</a>
        <span><?php echo $memory_quantity; ?></span>
        <a href="index.php?type=memory&quantity_change=1" class="quantity-button">+</a>
    </div>
    <div>Total Price: $<?php echo $memory_total_price; ?></div>
    <form method="get" action="components.php">
        <input type="hidden" name="type" value="memory">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($memory_price); ?>">
        <button type="submit" name="action" value="select_memory" class="select-button">Select Memory</button>
    </form>
</div>

<div class="row">
    <label for="gpu">GPU:</label>
    <div class="selected-item" id="gpu-selected">
        <?php echo $selected_gpu; ?>
    </div>
    <div class="price">
        <?php echo $gpu_price ? "Price: $$gpu_price" : ''; ?>
    </div>
    <div class="quantity-controls">
        <a href="index.php?type=gpu&quantity_change=-1" class="quantity-button">-</a>
        <span><?php echo $gpu_quantity; ?></span>
        <a href="index.php?type=gpu&quantity_change=1" class="quantity-button">+</a>
    </div>
    <div>Total Price: $<?php echo $gpu_total_price; ?></div>
    <form method="get" action="components.php">
        <input type="hidden" name="type" value="gpu">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($gpu_price); ?>">
        <button type="submit" name="action" value="select_gpu" class="select-button">Select GPU</button>
    </form>
</div>

<a href="customer.php">
    <button class="print-button">Order</button>
</a>

<?php include 'footer.php'; ?>
