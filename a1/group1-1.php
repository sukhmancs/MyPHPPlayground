<?php
/** I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. */

/**
 * @author Sukhmanjeet Singh
 * @package COMP 10260 Assignment 1
 * 
 * @version 202335.00
 */

$serial_number = $_GET['serial_number'];

/**
 * @param $serial_number string representing the serial_number
 * @return A string describing the radar pattern or Boolean false if no pattern is matched.
 */
function isRadarNote($serial_number) {    
    $numericPart = substr($serial_number, -7); // Get the last 7 characters of the serial number

    // Validate the input 
    if ($numericPart !== strrev($numericPart)) { 
        return false;
    }
    
    // Check if the input is consist of similar numbers
    if (count(array_unique(str_split($numericPart))) === 1 ) {
        return "Solid Serial Number";
    } else { // otherwise
        return "Radar Note";
    }
}

/**
 * @param $serial_number string representing the user input
 * @return string representing if it is ladder up, down or both
 */
function isLadderNote($serial_number) {
    $numericPart = substr($serial_number, -7); // Get the last 7 characters of the serial number
    $length = strlen($numericPart); // Get the length of the numeric part

    // Intialize some variables
    $isIncreasing = true;
    $isDecreasing = true;

    // Loop to check if it is both increasing or decreasing 
    for ($i = 1; $i < $length; $i++) {
        
        // If the pattern is 123456 or increasing order
        if ($numericPart[$i] != $numericPart[$i - 1] + 1) {
            $isIncreasing = false;  // Not an increasing pattern
        }

        // If the pattern is 654321 or decreasing order
        if ($numericPart[$i] != $numericPart[$i - 1] - 1) {
            $isDecreasing = false;  // Not a decreasing pattern
        }
    }

    $isIncreasingDecreasing = true; // Initialize as true for an up-down pattern

    // Loop to check if it is both increasing and decreasing 
    for ($i = 1; $i < $length; $i++) {
        
        // If first three values are increasing        
        if (($numericPart[$i] != $numericPart[$i - 1] + 1) && ($i <= 3)) {
            $isIncreasingDecreasing = false;  // Not an up-down pattern
        }
        
        // If the last three values are decreasing
        if (($numericPart[$i] != $numericPart[$i - 1] - 1) && ($i >= 5)) {
            $isIncreasingDecreasing = false;  // Not an up-down pattern
        }
    }
    
    if ($isIncreasing) {
        return "Ladar up";   // Return "Ladder up" if it's an increasing pattern
    } 
    if ($isDecreasing) {
        return "Ladar down";   // Return "Ladar down" if it's an decreasing pattern
    } 
    if ($isIncreasingDecreasing) {
        return "Lader up-down";   // Return "lader up-down" if it's an both increasing and decreasing pattern
    }
}

/**
 * @param $serial_number string representing the user input
 * @return A string describing the rotator pattern or Boolean false if no pattern is matched.
 */
function isRotatorNote($serial_number) {
    $numericPart = substr($serial_number, -7);  // Get the last 7 characters of the serial number

    // Initialize rotaterNoteDict 
    $rotatorNoteDict = ['0' => '0', '1' => '1', '6' => '9', '8' => '8', '9' => '6'];
    $length = strlen($numericPart); // Get length of numeric part of serial number

    $rotatedNumericPart = ""; // Initialize variable to store rotated numeric part

    // Loop over the length of the numericPart 
    for ($i = 0; $i < $length; $i++) {
        $number = $numericPart[$i];

        // Only replace values that are present inside $rotatorNoteDict
        if (!array_key_exists($number, $rotatorNoteDict)) {
            return false; // If not found, return false
        }
        $rotatedNumericPart .= $rotatorNoteDict[$number]; // Replace based on $rotatorNoteDict 
    }

    return strrev($numericPart) === $rotatedNumericPart ? "Rotator Note" : false; // Return "Rotator Note" if true, otherwise return false

}

/**
 * @param $serial_number string representing the user input
 * @return A string describing the binary pattern or Boolean false if no pattern is matched.
 */
function isBinaryNote($serial_number) {
    $numericPart = substr($serial_number, -7); // Get the last 7 characters of the serial number

    // Use a regular expression to check if the numeric part contains only 0s and 1s
    return preg_match('/^[01]+$/', $numericPart) === 1 ? "Binary Note" : false; // Return "Binary Note" if true, otherwise return false
}

// Check and output results for each function

// Check if it's a Radar Note
if ($result = isRadarNote($serial_number)) {
    echo "<li>$result</li>"; // Output the result within an HTML list item
}

// Check if it's a Ladder Note
if ($result = isLadderNote($serial_number)) {
    echo "<li>$result</li>"; // Output the result within an HTML list item
}

// Check if it's a Rotator Note
if ($result = isRotatorNote($serial_number)) {
    echo "<li>$result</li>"; // Output the result within an HTML list item
}

// Check if it's a Binary Note
if ($result = isBinaryNote($serial_number)) {
    echo "<li>$result</li>"; // Output the result within an HTML list item
}
?>