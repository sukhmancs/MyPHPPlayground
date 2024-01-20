<?php

/** I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. */

/**
 * @author Sukhmanjeet Singh
 * @package COMP 10260 Assignment 2
 * 
 * @version 202335.00
 */

/** 
 * Function to read and process the Pokemon text file
 * 
 * @return array Multi-dimensional associative array with two columns 'name' and 'image'
 */
function readPokemonFile() {
    $pokemonData = [];
    $file = 'pokemon.txt';

    $handle = fopen($file, 'r');

    // Will going to be false when "File not found", "Insufficient File permission", "Limited Resources, for example, inodes limit reached in linux", "File is already in use"
    if ($handle) {
        
        // Read the file until there are no names
        while (($name = fgets($handle)) !== false) {
            $image = fgets($handle);  // Returns a string containing line of a file
            $pokemonData[] = [  // Multi-dimensional array with two columns 'name' and 'image'
                'name' => htmlspecialchars(trim($name)), 
                'image' => htmlspecialchars(trim($image)) 
            ];
        }
        fclose($handle); // Close the resource
    }
    
    return $pokemonData; // Multi-dimensional associative array
}

/**
 * Function to read and process the Movies JSON file
 * 
 * @return array Multi-dimensional associtive array
 */
function readMoviesFile() {
    $moviesData = [];
    $file = 'movies.json';

    $handle = fopen($file, 'r');

    // To make sure that file was successfully opened
    if ($handle) {
        $contents = fread($handle, filesize($file));
        $moviesData = json_decode($contents, true);

        fclose($handle);
    }

    return filter_var_array($moviesData, FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 *  Function to sort data based on the selected sorting option
 * 
 * @param array $data Multi-dimensional associative array with 'name' and 'image' as the keys
 * @param string $sortOrder string containing only one character
 * @return array sorted Multi-dimensional associtave array
 */
function sortData($data, $sortOrder) {
    $names = array_column($data, 'name');

    // Sort in ascending order
    if ($sortOrder === 'a') {
        asort($names);
    } elseif ($sortOrder === 'd') { // // Sort in descending order
        arsort($names);
    } else {
        return $data; // No valid sorting order provided
    }

    $sortedData = [];
    foreach ($names as $key => $name) {
        $sortedData[] = $data[$key]; // Update $data based on indices of sorted array i.e. $names
    }

    return $sortedData;
}

// Handling the GET parameters
$choice = $_GET['choice'] ?? '';
$sort = $_GET['sort'] ?? '';

$choice = filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Process request and prepare response
$response = [];
if ($choice === 'pokemon') { // When the choice is pokemon
    $pokemonData = readPokemonFile();        
    $sortedData = sortData($pokemonData, $sort);
    $response = $sortedData;
} elseif ($choice === 'movies') { // When the choice is movies
    $moviesData = readMoviesFile();
    $sortedData = sortData($moviesData, $sort);
    $response = $sortedData;
} else {
    // Invalid choice
    $response = ['error' => 'Invalid choice'];
}

// Send JSON response
header('Content-Type: application/json'); 
echo json_encode($response);

?>