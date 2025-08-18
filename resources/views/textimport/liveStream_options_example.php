<?php
$videoId = isset($videoId) && $videoId !== null ? $videoId : 'xxxxxx';

return "https://www.youtube.com/embed/$videoId?autoplay=1";

// return 'https://www.youtube.com/embed/[videoID]?autoplay=1&mute=1
// autoplay=1: Das Video startet automatisch, sobald die Seite geladen wird.
// mute=1: Das Video wird ohne Ton abgespielt.
