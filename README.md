# DRMUY
REMEMBER TO DONWLOAD THE FFMPEG AND FFMPEGPROBE


This script is written in PHP and utilizes the N_M3U8-DL-RE library to decode MPD (MPEG-DASH) content and convert it into an M3U8 playlist for streaming purposes. (NOT EVEN FINISHED BUT WORKING)
This method will remove the jittler or freez in the video or audio when you convert from mpd to m3u8 and dont stops treaming, just need a good disk to store the ts file and remove them every x hors.

Please note that the script is still in development and might contain bugs, so it is not recommended to use it unless you are familiar with the code and its potential issues.

The main workflow of the script is as follows:

User Interface (UI): The script provides a user interface where the user can input the link to the MPD file, any encryption keys required for decryption, and also set up a proxy if necessary to bypass geographic restrictions.

Processing MPD: Once the user provides the necessary input, the script loads the MPD file using the provided link and extracts the required information from it. This includes obtaining the URLs for video and audio streams, as well as any encryption keys needed to access the content.

TS File Generation: The script then generates a TS (Transport Stream) file using the extracted video and audio streams. This TS file is a temporary file containing the combined media data.

FFmpeg Consumption: The script utilizes FFmpeg, a powerful multimedia framework, to consume the TS file and convert it into a suitable format for streaming, typically HLS (HTTP Live Streaming), represented by an M3U8 playlist.

M3U8 Creation: Finally, the script creates an M3U8 playlist file, containing references to the segmented video and audio streams, along with any required encryption information. This M3U8 file can be used for streaming the content on various compatible players.

Platform Support: The script is designed to work on various operating systems such as Ubuntu, CentOS, Debian, and Windows, ensuring a wide range of compatibility.

It's important to be cautious when using this script, especially since it's still in development and may have bugs. If you encounter any issues or need further assistance, you can ask for help from the script's author.

As always, when using third-party libraries and tools, ensure you have the necessary rights and permissions to access and use the content in compliance with copyright and licensing regulations.
