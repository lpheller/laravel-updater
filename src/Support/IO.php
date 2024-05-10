<?php

function red($string){
    return colorize($string, "red");
}

function green($string){
    return colorize($string, "green");
}

function yellow($string){
    return colorize($string, "yellow");
}

function blue($string){
    return colorize($string, "blue");
}

// Function to colorize text
function colorize($text, $color) {
    $colors = getTextColors();
    return $colors[$color] . $text . $colors['reset'];
}

function getTextColors() {
    return [
        'reset' => "\033[0m",
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[0;33m",
        'blue' => "\033[0;34m",
        'purple' => "\033[0;35m",
        'cyan' => "\033[0;36m",
        'white' => "\033[0;37m"
    ];
}
