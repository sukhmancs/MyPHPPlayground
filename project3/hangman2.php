<?php
/*

// Start or resume the session
session_start();

function InitializeParameters() {
    $_SESSION['secretLength'] = strlen($_SESSION['secret']);
    $_SESSION['guesses'] = '';
    $_SESSION['strikes'] = 0;
    $_SESSION['alphabet'] = range('a', 'z');
    $_SESSION['status'] = '';
}

function InitializeSession() {
    $_SESSION['secret'] = GetRandomWord();
    InitializeParameters();
}

/** Function to get a random word from the wordlist
 * Read the wordlist file into an array
 * Return a random word from the array
 
function GetRandomWord() {
    $wordlist = file('wordlist.txt', FILE_IGNORE_NEW_LINES); // Read the wordlist file into an array
    return $wordlist[array_rand($wordlist)];                 // Return a random word from the array
}

$mode = $_GET['mode'] ?? '';
$letter = $_GET['letter'] ?? '';

InitializeSession(); // Initialize the session

// Initialize temporary variables
$guesses = $_SESSION['guesses'];
$strikes = $_SESSION['strikes'];
$alphabet = $_SESSION['alphabet'];
$secret = $_SESSION['secret'];
$status = $_SESSION['status'];

//$letter = 'g';
if ($mode === 'reset') {     // If the game is being reset
    InitializeParameters();  // Initialize the parameters
} else if ($letter !== '') { // If a letter was guessed
    $letter = strtolower($letter);     // Convert the letter to lowercase
    if (in_array($letter, $guesses)) { // If the letter was already guessed
        $status = 'You already guessed this letter.';
    } else if (strpos($secret, $letter) !== false) {  // If the letter is in the secret word
        $status = 'Good guess!';
        $guesses .= $letter;
    } else { // If the letter is not in the secret word
        $status = 'Bad guess!';
        $guesses .= $letter;
        $strikes++;
    }
    $alphabet = array_diff($alphabet, $guesses);     // Remove the guessed letter from the alphabet              
}

/*$secretArr = str_split($secret);
$displayArr = array_map(function ($char) use ($guesses) {
    return in_array($char, $guesses) ? $char : '_';
}, $secretArr);
$display = implode(' ', $displayArr);

if ($_SESSION['strikes'] >= 7) {
    $status = "You lost! The word was $secret.";
} else if (!in_array('_', $displayArr)) {
    $status = 'You won!';
}

// Update the session variables
$_SESSION['guesses'] = $guesses;
$_SESSION['strikes'] += $strikes;
$_SESSION['alphabet'] = $alphabet;
$_SESSION['status'] = $status;  
$_SESSION['display'] = $display;

$response = [
    'guesses' => implode(' ', $_SESSION['guesses']),
    'alphabet' => implode(' ', $_SESSION['alphabet']),
    'secret' => $_SESSION['secret'],
    'strikes' => (int)$_SESSION['strikes'],
    'status' => $_SESSION['status'],
];

//print_r($response);

echo json_encode($response);
?>
*/

// Start or resume the session
session_start();

function InitializeParameters() {
    $_SESSION['secretLength'] = strlen($_SESSION['secret']);
    $_SESSION['guesses'] = [];
    $_SESSION['strikes'] = 0;
    $_SESSION['alphabet'] = range('a', 'z');
    $_SESSION['status'] = '';
}

function InitializeSession() {
    $_SESSION['secret'] = GetRandomWord();
    InitializeParameters();
}

/** Function to get a random word from the wordlist
 * Read the wordlist file into an array
 * Return a random word from the array
 */
function GetRandomWord() {
    $wordlist = file('wordlist.txt', FILE_IGNORE_NEW_LINES); // Read the wordlist file into an array
    return $wordlist[array_rand($wordlist)];                 // Return a random word from the array
}

function GetWinner($displayArr) {
    if ($_SESSION['strikes'] >= 7) {
        return "You lost! The word was {$_SESSION['secret']}.";
    } else if (!in_array('_', $displayArr)) {
        return "You won!";
    }
}

function GetDisplayArray($secret, $guesses) {
    $secretArr = str_split($secret);
    return array_map(function ($char) use ($guesses) {
        return in_array($char, $guesses) ? $char : '_';
    }, $secretArr);
}

//$wordlist = file('wordlist.txt', FILE_IGNORE_NEW_LINES);
//$secret = $wordlist[array_rand($wordlist)];
//$secretLength = strlen($secret);

if (empty($_SESSION)) {
    InitializeSession(); // Initialize the session
}

// Initialize temporary variables
$guesses = $_SESSION['guesses'];
$strikes = $_SESSION['strikes'];
$alphabet = $_SESSION['alphabet'];
$secret = $_SESSION['secret'];
$status = $_SESSION['status'];


$mode = $_GET['mode'] ?? '';
$letter = $_GET['letter'] ?? '';
if ($mode === "reset") {
    InitializeParameters();
} else if ($letter !== '') {
    $letter = strtolower($letter);
    if (in_array($letter, $guesses)) {
        $status = 'You already guessed this letter.';
    } else if (strpos($secret, $letter) !== false) {
        $status = 'Good guess!';
        $guesses[] = $letter;
    } else {
        $status = 'Bad guess!';
        $guesses[] = $letter;
        $strikes++;        
    }
    $alphabet = array_diff($alphabet, $guesses);
}

$displayArr = GetDisplayArray($secret, $guesses);
$display = implode(' ', $displayArr);

$status = GetWinner($displayArr);

// Update the session variables
$_SESSION['guesses'] = $guesses;
$_SESSION['strikes'] = $strikes;
$_SESSION['alphabet'] = $alphabet;
$_SESSION['status'] = $status;  
//$_SESSION['display'] = $display;

$response = [
    'guesses' => implode(' ', $_SESSION['guesses']),
    'alphabet' => implode(' ', $_SESSION['alphabet']),
    'secret' => $display,
    'strikes' => (int)$_SESSION['strikes'],
    'status' => $_SESSION['status'],
];

echo json_encode($response);
?>
