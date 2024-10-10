<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Debugging output to check if mysqliection is set
if (!$mysqli) {
    die("Database mysqliection failed.");
} else {
    echo "Database mysqliection successful.";
}

// Add new subscription
if(isset($_POST['add_subscription'])) {
    // Prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO yearly_subscription (user_id, full_name, email, contact_no, subscription_start_date, subscription_end_date, payment_status, subscription_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $user_id, $full_name, $email, $contact_no, $subscription_start_date, $subscription_end_date, $payment_status, $subscription_amount);

    // Set parameters and execute
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $subscription_start_date = $_POST['subscription_start_date'];
    $subscription_end_date = $_POST['subscription_end_date'];
    $subscription_amount = $_POST['subscription_amount'];
    $payment_status = $_POST['payment_status'];

    if ($stmt->execute()) {
        echo "New subscription added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Update subscription payment status
if(isset($_POST['update_subscription'])) {
    // Prepare and bind
    $stmt = $mysqli->prepare("UPDATE yearly_subscription SET payment_status=?, payment_date=?, payment_receipt=? WHERE subscription_id=?");
    $stmt->bind_param("sssi", $payment_status, $payment_date, $payment_receipt, $subscription_id);

    // Set parameters and execute
    $subscription_id = $_POST['subscription_id'];
    $payment_status = $_POST['payment_status'];
    $payment_date = $_POST['payment_date'];
    $payment_receipt = $_POST['payment_receipt'];

    if ($stmt->execute()) {
        echo "Subscription updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

$mysqli->close();
?>

<!-- HTML Form to Add and Update Subscription -->
<!DOCTYPE html>
<html>
<head>
    <title>Yearly Subscription</title>
</head>
<body>

<h2>Add New Subscription</h2>
<form method="post">
    User ID: <input type="text" name="user_id" required><br>
    Full Name: <input type="text" name="full_name" required><br>
    Email: <input type="email" name="email" required><br>
    Contact No: <input type="text" name="contact_no" required><br>
    Subscription Start Date: <input type="date" name="subscription_start_date" required><br>
    Subscription End Date: <input type="date" name="subscription_end_date" required><br>
    Subscription Amount: <input type="text" name="subscription_amount" required><br>
    Payment Status: <input type="text" name="payment_status" required><br>
    <input type="submit" name="add_subscription" value="Add Subscription">
</form>

<h2>Update Subscription</h2>
<form method="post">
    Subscription ID: <input type="text" name="subscription_id" required><br>
    Payment Status: <input type="text" name="payment_status" required><br>
    Payment Date: <input type="date" name="payment_date" required><br>
    Payment Receipt: <input type="text" name="payment_receipt" required><br>
    <input type="submit" name="update_subscription" value="Update Subscription">
</form>

</body>
</html>
