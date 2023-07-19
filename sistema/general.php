<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conn/conn.php';
require_once 'auth.php';

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
            </div>
      </div>
      
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>
   </body>
</html>