<?php
include('includes/config.php');



//room fee and seats
if(!empty($_POST["roomid"]) && !empty($_POST["gender"])) {
    $roomid = $_POST["roomid"];
    $gender = $_POST["gender"];

    // Prepare the SQL query to get rooms based on room_no and gender
    $query = "SELECT * FROM rooms WHERE room_no = ? AND gender = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $roomid, $gender);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = $res->fetch_assoc();

    // Return the data as JSON
    echo json_encode($data);

    // Close the statement and the connection
    $stmt->close();
    // $mysqli->close();
}



?>