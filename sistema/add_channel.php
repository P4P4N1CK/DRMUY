<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conn/conn.php';
require_once 'auth.php';
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $name = $_POST['name'];
    $keyU = $_POST['keyU'];
    $keyID = $_POST['keyID'];
    $proxy = $_POST['proxy'];
    $useProxy = $_POST['useProxy'];
    $url = $_POST['url'];

    // Generar los valores de saveDir, m3u8Dir y tmpDir basados en el nombre del canal
    $saveDir = 'stream/' . $name;
    $m3u8Dir = 'm3u8/' . $name;
    $tmpDir = 'temp';

    // Insertar el canal en la base de datos
    $query = "INSERT INTO canales (name, keyU, keyID, proxy, useProxy, url, saveDir, m3u8Dir, tmpDir) VALUES (:name, :keyU, :keyID, :proxy, :useProxy, :url, :saveDir, :m3u8Dir, :tmpDir)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':keyU', $keyU);
    $stmt->bindParam(':keyID', $keyID);
    $stmt->bindParam(':proxy', $proxy);
    $stmt->bindParam(':useProxy', $useProxy);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':saveDir', $saveDir);
    $stmt->bindParam(':m3u8Dir', $m3u8Dir);
    $stmt->bindParam(':tmpDir', $tmpDir);

    if ($stmt->execute()) {
        // Canal agregado exitosamente
        $message = "El canal ha sido agregado correctamente.";
    } else {
        // Error al agregar el canal
        $message = "Error al agregar el canal.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>DRMUY</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
   </head>
   <body class="bg-gray-900">
    <?php
    include "menu.php";
    ?>
      <div class="p-4 sm:ml-64">
             <div class="container mx-auto p-4">
            <div class="container mx-auto mt-8 text-gray-400">
        <h1 class="text-2xl font-bold mb-4">Agregar Canal</h1>

        <?php if (isset($message)): ?>
            <p class="bg-green-200 text-green-800 px-4 py-2 mb-4"><?php echo $message; ?></p>
        <?php endif; ?>

<form action="" method="post" class="space-y-6 text-gray-500">
    <div class="relative">
                <label class="top-2 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="name">Nombre:</label>

        <input class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" type="text" name="name" id="name" required>
    </div>
    <div class="relative">
                <label class=" top-2 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="keyU">Key U:</label>

        <input class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" type="text" name="keyU" id="keyU" required>
    </div>
    <div class="relative">
                <label class=" top-2 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="keyID">Key ID:</label>

        <input class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" type="text" name="keyID" id="keyID" required>
    </div>
    <div class="relative">
                <label class=" top-2 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="proxy">Proxy:</label>

        <input class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" type="text" name="proxy" id="proxy">
    </div>
    <div class="relative">
                <label class=" top-2 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="useProxy">Usar Proxy:</label>

        <select class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" name="useProxy" id="useProxy">
            <option value="true">Sí</option>
            <option value="false">No</option>
        </select>
    </div>
    <div class="relative">
                <label class=" top-1 left-4 text-gray-500 transition-all duration-300 ease-in-out" for="url">URL:</label>

        <input class="border border-gray-300 px-4 py-2 w-full text-sm text-black bg-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md text-gray-50" type="text" name="url" id="url" required>
    </div>
    <button class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center text-gray-50" type="submit">Agregar Canal</button>
</form>





    </div>
            </div>
      </div>
      
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/lib/index.min.js
"></script>
   </body>
</html>
