<?php

/** I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. */

/**
 * @author Sukhmanjeet Singh
 * @package COMP 10260 Assignment 2
 * 
 * @version 202335.00
 */

/**
 * Function to read the CSV file
 * 
 * @return array Mult-dimensional associative array with column of csv file as keys
 */
function readCSVFile() {                                
    
    // Read the uploaded CSV file which will going to have temporary file_name
    $file = fopen($_FILES['csvFile']['tmp_name'], 'r');
    $header = fgetcsv($file); // Get header row

    $data = [];
    while (($row = fgetcsv($file)) !== false) { // Get rows until we reach the end of csv file
        $row = filter_var_array($row, FILTER_SANITIZE_SPECIAL_CHARS);
        $data[] = array_combine($header, $row); // Associative array using header as keys
    }
    
    fclose($file);      
    return $data; // Mult-dimensinoal associative arrays with column as keys              
}

// Validate it is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if a file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        
        // File validation - check file type
        $file_info = pathinfo($_FILES['csvFile']['name']);
        if ($file_info['extension'] === 'csv') {                        

            // Check if sortColumn parameter is set and within bounds
            $sortColumn = isset($_POST['sortColumn']) ? max(1, min(3, $_POST['sortColumn'])) : 1;
            
            // Get CSV file as an multi-dimensional associative arrays with csv columns as keys
            $data = readCSVFile();

            // Define custom sorting function based on sortColumn
            usort($data, function ($a, $b) use ($sortColumn) {
                $key = array_keys($a)[$sortColumn - 1]; // Get the key to sort by
                return strnatcasecmp($a[$key], $b[$key]); // Natural sorting comparison
            });

            // Output the sorted data as JSON
            header('Content-Type: application/json');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            
            // Invalid file type
            echo "Please upload a CSV file.";
        }
    } else {
        // No file uploaded or an error occurred
        echo "Please select a file to upload.";
    }
} else {
    // If accessed via GET or other methods
    echo "Invalid request method.";
}
?>
