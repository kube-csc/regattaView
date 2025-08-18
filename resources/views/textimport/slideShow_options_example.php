<?php
$videoId = 'xxxxxxxxx';
$videoLaenge = 120000; // 2 Minuten Standard
return [
    'videoUrl' => "https://www.youtube.com/embed/$videoId?autoplay=1",
    'videoLaenge' => $videoLaenge,
];

// return 'https://www.youtube.com/embed/[videoID]?autoplay=1&mute=1
// autoplay=1: Das Video startet automatisch, sobald die Seite geladen wird.
// mute=1: Das Video wird ohne Ton abgespielt.
