<?php

/** I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. */

/**
 * @author Sukhmanjeet Singh
 * @package COMP 10260 Assignment 1
 * 
 * @version 202335.00
 */

$rows = $_GET['rows'];

/**
 * @param $rowNumber int representing the row number
 * @return string representing the generated row
 */
function createRow($rowNumber) {

    // add tr and td html tags 
    $output = "<tr>";
    $output .= "<td>";

    // add data $rowNumber times to the first column
    for ($i = 1; $i <= $rowNumber; $i++) {
        $output .= $rowNumber;
    }
    $output .= "</td>";

    // intialize sum
    $sum = 0;

    // sum the data from first column
    for ($i = 1; $i <= $rowNumber; $i++) {
        $sum += $rowNumber;
    }

    // add the data to the second column
    $output .= "<td>$sum</td>";

    // close the row tag
    $output .= "</tr>";
    return $output;
}

// create $rows number of rows
for ($row = 1; $row <= $rows; $row++) {
    echo createRow($row);
}
?>