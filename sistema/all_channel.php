<?php
require_once 'conn/conn.php';
require_once 'auth.php';
$query = "SELECT * FROM canales";
$stmt = $db->query($query);
$canales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>DRMUY</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
      <link href="https://vjs.zencdn.net/7.15.4/video-js.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.css" rel="stylesheet" />

   </head>
   <body class="bg-gray-900">
    <?php
    include "menu.php";
    ?>
      <div class="p-4 sm:ml-64">
         <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4 text-gray-500">Lista de Canales</h1>
            <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
            <div class="flex items-center justify-center bg-gray-900">
                <div class="col-span-12">
                    <div class="overflow-auto lg:overflow-visible">
                        <table class="table text-gray-400 border-separate space-y-6 text-sm">
                            <thead class="bg-gray-800 text-gray-500">
                                <tr>
                                    <th class="p-3">ID</th>
                                    <th class="p-3 text-center">Nombre</th>
                                    <th class="p-3 text-center">Proxy</th>
                                    <th class="p-3 text-center">PID FFMPEG</th>
                                    <th class="p-3 text-center">PID M3U8</th>
                                    <th class="p-3 text-center">Estado</th>
                                    <th class="p-3 text-center">Running Time</th>
                                    <th class="p-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($canales as $canal) {
                                    $id = $canal['id'];
                                    $nombre = $canal['name'];
                                    $proxy = $canal['proxy'];
                                    $pidFFMPEG = $canal['pidffmpeg'];
                                    $pidM3U8 = $canal['pidm3u8'];
                                    $timeStarted = $canal['time_started']; // Example: July 15, 2023, 12:00:00
                                    if (!empty($timeStarted)) {
                                        $timeStartedTimestamp = strtotime($timeStarted);
                                        $currentTime = time();
                                        $runningTime = $currentTime - $timeStartedTimestamp;
                                        $hours = floor($runningTime / (60 * 60));
                                        $minutes = floor(($runningTime % (60 * 60)) / 60);
                                        $seconds = $runningTime % 60;
                                        $timeRunning = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                                    } else {
                                        $timeRunning = "Not available";
                                    }
                                    $estado = ($pidFFMPEG && $pidM3U8) ? 'Running' : 'Stopped';
                                    $estadoColor = ($pidFFMPEG && $pidM3U8) ? 'green-600' : 'red-600';
                                ?>
                                    <tr class="bg-gray-800">
                                        <td class="p-3 text-center"><?php echo $id; ?></td>
                                        <td class="p-3 text-center"><?php echo $nombre; ?></td>
                                        <td class="p-3 text-center"><?php echo $proxy; ?></td>
                                        <td class="p-3 text-center"><?php echo $pidFFMPEG; ?></td>
                                        <td class="p-3 text-center"><?php echo $pidM3U8; ?></td>
                                        <td class="p-3 text-center">
                                            <span id="estado-<?php echo $id; ?>" class="text-<?php echo $estadoColor; ?>">
                                                <?php echo $estado; ?>
                                            </span>
                                        </td>
                                        <td class="p-3 text-center"><span id="runningTime-<?php echo $id; ?>"><?php echo $timeRunning; ?></span></td>
                                        <td class="p-3 text-center">
                                            <a href="#" class="text-gray-400 hover:text-gray-100 mr-2" onclick="playM3U8('<?php echo $id; ?>', '<?php echo $nombre; ?>')">
                                                <i class="material-icons-outlined text-base text-green-600">live_tv</i>
                                            </a>

                                            <a href="#" class="text-gray-400 hover:text-gray-100 mr-2" onclick="playChannel(<?php echo $id; ?>)">
                                                <i class="material-icons-outlined text-base text-green-600">play_arrow</i>
                                            </a>
                                            <a href="#" class="text-gray-400 hover:text-gray-100 mx-2" onclick="stopChannel(<?php echo $id; ?>)">
                                                <i class="material-icons-outlined text-base text-red-400">stop</i>
                                            </a>
                                            <a href="#" class="text-gray-400 hover:text-gray-100 mx-2">
                                                <i class="material-icons-outlined text-base text-blue-600">edit</i>
                                            </a>
                                            <a href="delete_channel.php?channelId=<?php echo $id; ?>" class="text-gray-400 hover:text-gray-100 ml-2" onclick="showDeleteConfirmation(event)">
                                                <i class="material-icons-round text-base text-red-600">delete_outline</i>
                                            </a>
                                            <a href="#" class="text-gray-400 hover:text-gray-100 ml-2" onclick="deleteTSFile(<?php echo $id; ?>)">
                                                <i class="material-icons-round text-base text-red-200">delete_sweep</i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <style>
                .table {
                    border-spacing: 0 15px;
                }

                i {
                    font-size: 1rem !important;
                }

                .table tr:first-child td:first-child {
                    border-radius: .625rem 0 0 0;
                }

                .table tr:first-child td:last-child {
                    border-radius: 0 .625rem 0 0;
                }

                .table tr:last-child td:first-child {
                    border-radius: 0 0 0 .625rem;
                }

                .table tr:last-child td:last-child {
                    border-radius: 0 0 .625rem 0;
                }

                .starting {
                    color: orange;
                }
                #m3u8PlayerWrapper {
                    width: 100%;
                    height: 0;
                    padding-bottom: 56.25%; /* Proporción 16:9 para video responsivo */
                    position: relative;
                    border-radius: 10px; /* Border radius personalizado */
                    overflow: hidden;
                }
                
                #m3u8Player {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                }
    .swal2-popup {
        background-color: #222 !important;
    }

    /* Estilos para el título del modal */
    .swal2-title {
        color: #fff !important;
    }
            </style>
         </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://vjs.zencdn.net/7.15.4/video.min.js"></script>

      <script>
          const domain = window.location.origin;

        function playM3U8(channelId, channelName) {
            // Create the modal content
            const modalContent = `
            <div class="p-4">
                <h2 class="text-xl mb-4 text-white">${channelName}</h2>
                <div id="m3u8PlayerWrapper" class="flex items-center justify-center">
                    <video id="m3u8Player" class="video-js vjs-default-skin" controls></video>
                </div>
            </div>

            `;

            // Open the modal using SweetAlert2
            Swal.fire({
                html: modalContent,
                width: '80%',
                showConfirmButton: false,
                allowOutsideClick: true,
                didOpen: () => {
                    // Get the video element
                    const videoPlayer = videojs('m3u8Player');

                    // Set the source URL of the M3U8 file
                    const m3u8URL = `${domain}/sistema/bin/m3u8/${channelName}/${channelName}.m3u8`;
                    videoPlayer.src({
                        src: m3u8URL,
                        type: 'application/vnd.apple.mpegurl'
                    });
                    videoPlayer.play();
                },
                willClose: () => {
                    // Stop and reset the video player when the modal is closed
                    const videoPlayer = videojs('m3u8Player');
                    videoPlayer.pause();
                    videoPlayer.src('');
                    videoPlayer.dispose();
                }
            });
        }
      
      function deleteTSFile(channelId) {
            Swal.fire({
                title: 'Eliminar TS del canal',
                text: '¿Estás seguro de que deseas eliminar el TS del canal si está bugeado o sigue generándose?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('channelId', channelId);
        
                    $.ajax({
                        url: 'bin/delete_ts_file.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire('TS del canal eliminado!', '', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Oops...', 'Algo salió mal!', 'error');
                        }
                    });
                }
            });
        }
        
        function showDeleteConfirmation(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Eliminar canal',
                text: '¿Estás seguro de que deseas eliminar este canal?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.href;
                }
            });
        }
        
        function showDeleteConfirmationTs(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Eliminar TS del canal',
                text: '¿Estás seguro de que deseas eliminar el ts del canal si esta bugeado o sigue generandose?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.href;
                }
            });
        }
    
       function playChannel(channelId) {
            Swal.fire({
                title: 'Play Channel',
                text: 'Do you want to play this channel?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Play',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('channelId', channelId);
        
                    // Change the "Estado" text to "Starting" and apply the "starting" CSS class
                    $('#estado-' + channelId).text('Starting').addClass('starting');
        
                    $.ajax({
                        url: 'bin/play_channel.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire('Channel played!', '', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Oops...', 'Something went wrong!', 'error');
                        }
                    });
                }
            });
        }
        function stopChannel(channelId) {
            Swal.fire({
                title: 'Stop Channel',
                text: 'Do you want to stop this channel?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Stop',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('channelId', channelId);
                    $.ajax({
                        url: 'bin/stop_channel.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire('Channel stopped!', '', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Oops...', 'Something went wrong!', 'error');
                        }
                    });
                }
            });
        }

        // Function to update the running time using AJAX
        function updateRunningTime(channelId) {
            // Send an AJAX request to fetch the updated running time
            $.ajax({
                url: 'update_running_time.php',
                type: 'GET',
                data: { channelId: channelId },
                success: function(response) {
                    // Update the running time on the page
                    $('#runningTime-' + channelId).text(response);
                },
                complete: function() {
                    // Schedule the next update after 1 second
                    setTimeout(function() {
                        updateRunningTime(channelId);
                    }, 1000);
                }
            });
        }

        // Call the updateRunningTime function for each channel
        <?php foreach ($canales as $canal) { ?>
            updateRunningTime(<?php echo $canal['id']; ?>);
        <?php } ?>
      </script>
   </body>
</html>
      </script>
   </body>
</html>
