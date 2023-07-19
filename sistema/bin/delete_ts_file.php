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
// Consultar la información del canal en la base de datos
$stmt = $db->prepare("SELECT * FROM canales WHERE id = :channelId");
$stmt->bindParam(':channelId', $channelId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// Obtener los valores de las columnas
$name = $row['name'];
$saveDir = $row['saveDir'];
// Eliminar el archivo .ts
$fileToDelete = $saveDir . "/" . $name . ".ts";
if (file_exists($fileToDelete)) {
    unlink($fileToDelete);
    echo "El archivo $fileToDelete ha sido eliminado.";
} else {
    echo "El archivo $fileToDelete no existe.";
}
?>