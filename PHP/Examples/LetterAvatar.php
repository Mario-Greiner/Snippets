<?php
//Set the required headers
header('Content-type: image/jpeg');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//Import the class
require $_SERVER['DOCUMENT_ROOT'] . '/PHP/LetterAvatar.php';

//Create Avatar only with required arguments
$avatar = new LetterAvatar($text);

//Create Avatar with all available arguments (Name, Width, Height, Fontsize, Backgroundcolor, Textcolor)
$avatar = new LetterAvatar($name, 200, 200, 50, '2e54d3', 'fff');

//Display the image to browser
$avatar->display();