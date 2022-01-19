<?php
function extend($text) {
    # get the commands from the json file
    # the file is a json object with 3 properties:
    # - commands: an array of commands
    # - regex: an array of regexes that are always good
    # - interfaces: an array of interface names that are used with n/n at the end
    $commands = json_decode(file_get_contents('commands.json'), true);
    $lines = preg_split("/\r\n|\n|\r/", strtolower($text)); # convert to lowercase and split into lines
    for ($i=0; $i < count($lines); $i++) { 
        $lines[$i] = line($lines[$i], $commands);
    }
    return implode("\n", $lines);
}

function line($text, $commands) {
    $words = explode(" ", $text); # Split text into words
    if (count($words) > 0) {
        $words[0] = command($words[0], $commands);
    }
    for ($i=1; $i < count($words); $i++) { 
        $words[$i] = word(str_replace(" ", "", $words[$i]), $commands);
    }
    return implode(" ", $words);
}

function command($text, $commands) {
    $l = strlen($text);
    $r = "";
    # for each command this checks if a commands start with the word, if more than one commands start with the word, it will return the word
    for ($i=0; $i < count($commands["commands"]); $i++) {
        if ($commands["commands"][$i] == $text) {
            return $text;
        } else if(substr($commands["commands"][$i], 0, $l) == $text) {
            if ($r == "") {
                $r = $commands["commands"][$i];
            } else {
                return $text;
            }
        }
    }
    # if no command is found, return the word with error, else the command
    if ($r == "") {
        $spl = str_split($text)[0];
        if ($spl[0] == "*" && $spl[strlen($spl) - 1] == "*") { # Check if the word is already checked as an error
            return $text;
        }
        return "*" . $text . "*"; 
    } else {
        return $r;
    }
}

function word($text, $commands) { # for each word
    $l = strlen($text);
    $r = "";
    # check if contains a number/number
    if (preg_match("/([0-9][0-9]?\/[0-9][0-9]?)/", $text)) {
        # get index of number/number and divide the word in [interfaceName] and [number/number]
        $p = -1;
        if(preg_match("/([0-9][0-9]?\/[0-9][0-9]?)/", $text, $matches, PREG_OFFSET_CAPTURE)) {
            $p = $matches[0][1];
        }
        $sub = substr($text, 0, $p);
        $sc = strlen($sub);
        $num = substr($text, $p, $l-$sc);
        # Check the interface that start with the first part of the word
        for ($i=0; $i < count($commands["interfaces"]); $i++) { 
            if (substr($commands["interfaces"][$i], 0, $sc) == $sub) {
                return $commands["interfaces"][$i] . $num;
            }
        }
    }
    # for each command this checks if a keyword start with the word, if more than one keyword start with the word, it will return the word
    for ($i=0; $i < count($commands["keywords"]); $i++) {
        if ($commands["keywords"][$i] == $text) {
            return $text;
        } else if(substr($commands["keywords"][$i], 0, $l) == $text) {
            if ($r == "") {
                $r = $commands["keywords"][$i];
            } else {
                return $text;
            }
        }
    }

    # for each regex if the word matches the regex, it will return the word
    for ($i=0; $i < count($commands["regex"]); $i++) {
        if(preg_match($commands["regex"][$i], $text)) {
            return $text;
        }
    }

    # if no command is found, return the word with error, else the command
    if ($r == "") {
        $spl = str_split($text)[0];
        if ($spl[0] == "*" && $spl[strlen($spl) - 1] == "*") { # Check if the word is already checked as an error
            return $text;
        }
        return "*" . $text . "*"; 
    } else {
        return $r;
    }
}
?>
