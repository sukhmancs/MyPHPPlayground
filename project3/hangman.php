<?php
/**
 * I Sukhmanjeet Singh, 000838215, certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else.
 * @author Sukhmanjeet Singh <s.sukhmanjeet-singh@mohawkcollege.ca>
 * @package COMP 10260 Assignment 3
 * 
 * @version 2023.35.0
 */

/**
 * Class to represent the Hangman Game
 */
class HangmanGame 
{
    private $secret;         // secret word
    private $guesses = [];   // array of guessed letters
    private $strikes = 0;    // number of strikes
    private $alphabet;       // array of letters in the alphabet
    private $status;         // status of the game
    private $display = [];   // array to represent the guessed letters

    /**
     * Constructor
     */
    public function __construct() 
    {
        session_save_path(dirname(__FILE__) . '/sessions');  // Set a custom session save path
        session_set_cookie_params(1800);          // Set session expiration time to 30 minutes
        ini_set('session.gc_maxlifetime', 1800);  // if the session is inactive for 30 minutes, it will be deleted
        session_start();             // Start or resume the session
        $this->InitializeSession();  // Initialize the session
        $this->UpdateParameters();   // Update the parameters
    }

    /**
     * Function to initialize the session
     * If the session is empty, initialize the session with one random word per session
     */
    private function initializeSession()
    {
        if (empty($_SESSION)) { // One Random Word Per Session
            $_SESSION['secret'] = $this->getRandomWord();            
            $this->ResetSession();
        }

        $this->secret = $_SESSION['secret'];  // Get the secret word
    }

    /**
     * Function to update the parameters
     */
    private function UpdateParameters() 
    {        
        // Update the parameters
        $this->secret = $_SESSION['secret'];
        $this->guesses = $_SESSION['guesses'];
        $this->strikes = $_SESSION['strikes'];
        $this->alphabet = $_SESSION['alphabet'];
        $this->status = $_SESSION['status'];
    }

    /**
     * Function to reset the session
     */
    private function ResetSession() 
    {
        // Reset the session
        $_SESSION['guesses'] = [];
        $_SESSION['strikes'] = 0;
        $_SESSION['alphabet'] = range('a', 'z');        
        $_SESSION['status'] = 'You are playing a game now.';
    }

    /**
     * Function to update the session variables
     */
    private function UpdateSession()
    {
        // Update the session variables
        $_SESSION['secret'] = $this->secret;
        $_SESSION['guesses'] = $this->guesses;
        $_SESSION['strikes'] = $this->strikes;
        $_SESSION['alphabet'] = $this->alphabet;
        $_SESSION['status'] = $this->status;
    }

    /**
     * Function to get a random word from the wordlist
     * Read the wordlist file into an array
     * Return a random word from the array
     */
    public function GetRandomWord() 
    {
        $wordlist = file('wordlist.txt', FILE_IGNORE_NEW_LINES); // Read the wordlist file into an array
        return $wordlist[array_rand($wordlist)];                 // Return a random word from the array
    }

    /**
     * Function to get the winner
     * @param array $displayArr array to represent the guessed letters
     * @return string winner
     */
    public function GetWinner($displayArr) 
    {
        if ($this->strikes >= 7) { // If strikes are 7 or more            
            return "You lost! The word was {$this->secret}.";
        } else if (!in_array('_', $displayArr)) { // If there are no more blanks
            return "You won!";
        } else {
            return 'Game is being played';
        }
    }

    /**
     * Function to get an array to represent the guessed letters
     * @param string $secret secret word
     * @param array $guesses array of guessed letters
     * @return array array to represent the guessed letters
     */
    public function GetDisplayArray($secret, $guesses) 
    {
        $secretArr = str_split(trim($secret));  // Convert the secret word to an array
        return array_map(function ($char) use ($guesses) {  // [s, e, c, r, e, t] => [c, r, e] => [_, e, c, r , e , _]
            return in_array($char, $guesses) ? $char : '_';
        }, $secretArr);
    }

    /**
     * Function to get the response
     * @return array current state of the game
     */
    public function GetResponse() {
        return [ // Return the response
            'guesses' => implode(' ', $_SESSION['guesses']),
            'alphabet' => implode(' ', $_SESSION['alphabet']),
            'secret' => $this->display,
            'strikes' => (int)$_SESSION['strikes'],
            'status' => $_SESSION['status'],
        ];
    }

    /**
     * Function to play the game
     * @param string $mode mode of the game
     * @param string $letter letter guessed
     * @return array current state of the game
     */
    public function PlayGame($mode, $letter) {

        if ($this->status === "You won!" && $mode !== "reset") {
            $this->display = $this->secret;  // Reveal the secret word
            return $this->GetResponse();
        }

        // If strikes are 7 or more, return the current state without updating
        if ($this->strikes >= 7 && $mode !== "reset") {            
            $this->display = $this->secret;  // Reveal the secret word
            return $this->GetResponse();
        }

        // If the game is being reset, reset the session and update the parameters
        if ($mode === "reset") {
            $this->ResetSession();     // Reset the session            
            $this->UpdateParameters(); // Update the parameters

        } else if ($letter !== '') {       // If a letter is being guessed, update the parameters
            $letter = strtolower($letter); // Convert the letter to lowercase
            if (in_array($letter, $this->guesses)) { // If the letter was already guessed
                $this->status = 'You already guessed this letter.'; 
            } else if (strpos($this->secret, $letter) !== false) { // If the letter is in the secret word
                $this->status = 'Good guess!';
                $this->guesses[] = $letter;  // Add the letter to the guesses
            } else {  // If the letter is not in the secret word
                $this->status = 'Bad guess!';
                $this->guesses[] = $letter;
                $this->strikes++;            // Increment the strikes
            }
            $this->alphabet = array_diff($this->alphabet, $this->guesses);    // Remove the guessed letter from the alphabet
        }

        $displayArr = $this->GetDisplayArray($this->secret, $this->guesses);  // Get an array to represent the guessed letters  
        $this->display = implode(' ', $displayArr);     // Get a string to represent the guessed letters
        $this->status = $this->GetWinner($displayArr);  // Determine the winner        

        $this->UpdateSession();         // Update the session variables        
        return $this->GetResponse();    // Return the response
    }
}

// Create a new instance of the HangmanGame class
$hangmanGame = new HangmanGame();

// Get the mode and letter from the query string
$mode = isset($_GET['mode']) ? filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_SPECIAL_CHARS) : '';
$letter = isset($_GET['letter']) ? filter_input(INPUT_GET, 'letter', FILTER_SANITIZE_SPECIAL_CHARS) : '';

$response = $hangmanGame->playGame($mode, $letter); // Play the game

echo json_encode($response); // Provide JSON encoded array
?>