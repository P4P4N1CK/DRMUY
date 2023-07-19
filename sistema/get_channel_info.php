<?php
// Include the database connection file
include '../conn/conn.php';
require_once 'auth.php';

// Obtener el ID del canal
$channelId = $_GET['channelId']; // Suponiendo que se pasará el ID del canal como parámetro en la URL

// Consultar la información del canal en la base de datos
$stmt = $db->prepare("SELECT * FROM canales WHERE id = :channelId");
$stmt->bindParam(':channelId', $channelId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener los valores de las columnas
$name = $row['name'];
$saveDir = $row['saveDir'];
$m3u8Dir = $row['m3u8Dir'];
$tmpDir = $row['tmpDir'];
$key = $row['keyU'];
$keyID = $row['keyID'];
$proxy = $row['proxy'];
$useProxy = $row['useProxy'];
$url = $row['url'];

// Crear la carpeta de saveDir si no existe
if (!is_dir($saveDir)) {
    mkdir($saveDir, 0755, true);
}

// Crear la carpeta de m3u8Dir si no existe
if (!is_dir($m3u8Dir)) {
    mkdir($m3u8Dir, 0755, true);
}

// Ejecutar el primer comando para descargar el archivo .ts
$command1 = './N_m3u8DL-RE \
--save-dir ' . $saveDir . ' \
--del-after-done true \
--save-name ' . $name . ' \
--tmp-dir ' . $tmpDir . ' \
"' . $url . '" \
--live-real-time-merge true \
--thread-count 1 \
--live-wait-time 2 \
--live-keep-segments false \
--mp4-real-time-decryption true \
--live-pipe-mux true \
-sv best \
-sa all \
--key ' . $keyID . ':' . $key . ' \
--use-system-proxy ' . $useProxy . ' \
--custom-proxy ' . $proxy . ' \
-H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36" > /dev/null 2>&1 & echo $!';

$output1 = shell_exec($command1);
$pid1 = intval($output1);

// Esperar 30 segundos para asegurarse de que el archivo .ts esté listo
sleep(30);

// Ejecutar el segundo comando para convertir el archivo .ts a .m3u8
$command2 = '/www/wwwroot/goplaytv.fun/sistema/bin/ffmpeg -re -i /www/wwwroot/goplaytv.fun/sistema/bin/stream/' . $name . '/' . $name . '.ts -c copy -f hls -hls_time 5 -hls_list_size 4 -hls_flags delete_segments /www/wwwroot/goplaytv.fun/sistema/bin/' . $m3u8Dir . '/' . $name . '.m3u8 > /dev/null 2>&1 & echo $!';
$output2 = shell_exec($command2);
$pid2 = intval($output2);

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Update the PID and start time in the database
$stmt = $db->prepare("UPDATE canales SET pidffmpeg = :pidffmpeg, pidm3u8 = :pidm3u8, time_started = :timeStarted WHERE id = :channelId");
$stmt->bindParam(':pidffmpeg', $pid2);
$stmt->bindParam(':pidm3u8', $pid1);
$stmt->bindParam(':timeStarted', $currentDateTime);
$stmt->bindParam(':channelId', $channelId);
$stmt->execute();

echo "PID del proceso N_m3u8DL-RE: " . $pid1 . "<br>";
echo "PID del proceso ffmpeg: " . $pid2 . "<br>";

// Esperar 20 segundos
sleep(20);

// Eliminar el archivo .ts
$fileToDelete = $saveDir . "/" . $name . ".ts";
if (file_exists($fileToDelete)) {
    unlink($fileToDelete);
    echo "El archivo $fileToDelete ha sido eliminado.";
} else {
    echo "El archivo $fileToDelete no existe.";
}
?>
