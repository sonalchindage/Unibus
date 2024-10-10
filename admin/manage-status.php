<?php
session_start();
$aid=$_SESSION['id'];
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from POST request
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $regno = isset($_POST['regno']) ? $_POST['regno'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    // Validate inputs
    if (!empty($status) && !empty($regno) && !empty($year)) {
        // Update the registration table
        // Assuming you have a database connection $conn

        $stmt = $mysqli->prepare("UPDATE `registration` SET `status`=? WHERE `regno`=? AND `duration`=?");
        $stmt->bind_param("sss", $status, $regno, $year);

        if ($stmt->execute()) {
            echo "Status updated successfully";
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid input";
    }
}

?>
