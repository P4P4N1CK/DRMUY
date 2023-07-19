<?php
// Include the database connection file
include '../conn/conn.php';
require_once '../auth.php';

// Check if the channel ID is provided
if (!isset($_POST['channelId'])) {
    // Handle the case when the channel ID is not provided
    echo 'Channel ID not provided.';
    exit();
}

// Get the channel ID from the POST data
$channelId = $_POST['channelId'];

// Query the database to fetch the PID values
$stmt = $db->prepare("SELECT pidffmpeg, pidm3u8 FROM canales WHERE id = :channelId");
$stmt->bindParam(':channelId', $channelId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pidFFMPEG = $row['pidffmpeg'];
$pidM3U8 = $row['pidm3u8'];

// Check if the PIDs are valid
if ($pidFFMPEG && $pidM3U8) {
    // Execute the command to stop the FFMPEG process
    $commandFFMPEG = 'kill ' . $pidFFMPEG;
    exec($commandFFMPEG);

    // Execute the command to stop the N_m3u8DL-RE process
    $commandM3U8 = 'kill ' . $pidM3U8;
    exec($commandM3U8);

    // Update the database to clear the PID values and time_started
    $stmt = $db->prepare("UPDATE canales SET pidffmpeg = NULL, pidm3u8 = NULL, time_started = NULL WHERE id = :channelId");
    $stmt->bindParam(':channelId', $channelId);
    $stmt->execute();

    // Return a success message
    echo 'Channel stopped successfully.';
} else {
    // Return an error message if the PIDs are not available
    echo 'PIDs not found for the channel.';
}
?>
