<?php
/**
 * I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else.
 * @author Sukhmanjeet Singh <s.sukhmanjeet-singh@mohawkcollege.ca>
 * @package COMP 10260 Assignment 3
 * 
 * @version 2023.35.0
 */

// Set a custom session save path
session_save_path(dirname(__FILE__) . '/sessions');

// Set session expiration time to 30 minutes
session_set_cookie_params(1800); // lifitime of the session cookie is 30 minutes

// Adjust the session garbage collection timeout
ini_set('session.gc_maxlifetime', 1800); // if the session is inactive for 30 minutes, it will be deleted

// Start or resume the session
session_start();

/** Function to initialize the parameters
 * Set the number of stones to 20
 * Set the number of moves to 0
 * Set the player to computer
 * Set the winner to Game in progress
 */
function IntializeParameters() {
    $_SESSION['count'] = 20;                  // number of stones
    $_SESSION['move'] = 0;                    // number of moves player/computer will remove
    $_SESSION['player'] = "computer";         // player or computer
    $_SESSION['winner'] = "Game in progress"; // winner of the game
}

/** Function to determine the optimal move
 * If the remainder of stones divided by 4 is 3, then remove 2 stones
 * If the remainder of stones divided by 4 is 2, then remove 1 stone
 * If the remainder of stones divided by 4 is 0, then remove 3 stones
 * Otherwise, remove a random number of stones
 */
function GetOptimalMove($stones) {
    $remainder = $stones % 4; // Remainder of stones divided by 4
    
    // Determine the optimal move
    switch ($remainder) {
        case 3: // If remainder is 3, remove 2 stones
            return 2;
        case 2: // If remainder is 2, remove 1 stone
            return 1;
        case 0: // If remainder is 0, remove 3 stones
            return 3;
        default: // default to random move
            return rand(1, 3);
    }
}

/** Function to determine the player
 * If the previous player was the computer, then the player is the player i.e. user
 * If the previous player was the player, then the player is the computer
 */
function TakeTurns() {
    return $_SESSION['player'] = ($_SESSION['player'] === "player") ? "computer" : "player";
}

/** Function to determine the winner
 * If the count is less than or equal to 0, then the winner is the previous player
 * Otherwise, the winner is undetermined
 */
function GetWinner() {

    $_SESSION['winner'] = "Undetermined."; // Default winner to undetermined

    // Whoever picks the last stone loses
    if ($_SESSION['count'] <= 0) {        
        $_SESSION['winner'] = "{$_SESSION['previous_player']} is the winner";            
        $_SESSION['count'] = 0; // Reset the count to 0
    }             
}

/** Function to update the game status
 * Update the move
 * Update the count
 */
function UpdateGameStatus($move) {

    // Update the move 
    $_SESSION['move'] = $move;

    // Make sure the count is not negative
    $_SESSION['count'] -= $_SESSION['move']; 
}

// Initialize players choices
$mode = filter_input(INPUT_GET, 'mode', FILTER_VALIDATE_INT, [
    'options' => ['default' => 0, 'min_range' => 0, 'max_range' => 1]
]); // 0 for reset, 1 for play, default to reset

$difficulty = filter_input(INPUT_GET, 'difficulty', FILTER_VALIDATE_INT, [
    'options' => ['default' => 0, 'min_range' => 0, 'max_range' => 1]
]); // 0 for easy, 1 for optimal, default to easy

$playerMove = filter_input(INPUT_GET, 'player_move', FILTER_VALIDATE_INT, [
    'options' => ['default' => 1, 'min_range' => 1, 'max_range' => 3]
]); // number of stones player will remove, default to 1

// Check if the game is being reset
if ($mode === 0 || empty($_SESSION)) {

    // Reset game with initial parameters    
    IntializeParameters();

} elseif ((!($_SESSION['count'] <= 0))) { // Determine if the game is over
    
    // Save the previous player
    $_SESSION['previous_player'] = $_SESSION['player']; // remove me

    // Determine the player
    $player = TakeTurns();    

    // Determine the move
    if ($player === "player") {
        
        // Player's turn
        $move = $playerMove;
    } else {        
        // Computer's turn
        $move = ($difficulty === 1) ? getOptimalMove($_SESSION['count']) : rand(1, 3);
    }
    
    UpdateGameStatus($move); // Update the move and count   
}

// Determine the winner
GetWinner();    

// Provide JSON encoded array
$response = [
    'move' => (int)$_SESSION['move'], 
    'stones' => (int)$_SESSION['count'],
    'player' => $_SESSION['player'],
    'winner' => $_SESSION['winner'],
];

// Send the response
echo json_encode($response);
?>
