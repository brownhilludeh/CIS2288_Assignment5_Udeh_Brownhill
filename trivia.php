<?php
/**
 * Holland College - CIS2288 Internet Programming Assignment 5 State Management
 * Trivia Game
 *
 * trivia.php
 * TODO: check these in order and make sure they are correct
 * Requirements covered:
 *  - Session-based state management
 *  - Loads any number of questions from triviaQuestions.txt (tab-separated)
 *  - Non-empty validation (case-insensitive checking)
 *  - Progress indicator (Question X of N)
 *  - Results table with colored rows (green for correct, red for incorrect)
 *  - Restart link (handled on same page)
 *  - Post-Redirect-Get pattern to prevent duplicate submissions on refresh
 *  
 * @depends on triviaQuestions.txt
 * @dependencies bootstrap.min.css, bootstrap.min.js
 * @author Brownhill Udeh <budeh@hollandcollege.com>
 * @author John Ekpigun <jekpigun@hollandcollege.com>
 * @version 5.1
 * @since 2025-11-08
 */

// Start the session
session_start();

// Path to trivia questions file
$filePath = __DIR__ . '/triviaQuestions.txt';

// Handles restart / unset session data
if (isset($_GET['restart'])) {
    unset($_SESSION['question_index'], $_SESSION['answers'], $_SESSION['started']);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Load questions from file as an array
$questions = [];

// Validate file exists and is readable or return error
if (!file_exists($filePath) || !is_readable($filePath)) {
    $file_error = "TriviaQuestions.txt file not found or not readable.";
} elseif (filesize($filePath) == 0) {
    $file_error = "TriviaQuestions.txt file is empty.";
} else {
    // Read file and parse questions
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Me splitting line tab in question/answer text
        $parts = explode("\t", $line, 2);
        
        // TODO: Bro check this and ensure we have both question and answer
        if (count($parts) === 2) {
            $question_text = trim($parts[0]);
            $answer_text = trim($parts[1]);
            
            // Only add if both are non-empty (changed from OR to AND)
            if ($question_text !== '' && $answer_text !== '') {
                $questions[] = [
                    'question' => $question_text,
                    'answer' => $answer_text
                ];
            }
        }

        // TODO: Guy check this orderly
        // echo count($lines) . '<br>'; This is what we will use to render all();
        // echo $line . '<br>';
        // echo count($parts) . '<br>';
        // echo $question_text . '<br>';
        // echo $answer_text . '<br>';

    }
}

// Initialize session data for new game
$total_questions = count($questions);
if ($total_questions > 0 && !isset($_SESSION['started'])) {
    $_SESSION['question_index'] = 0;     // Current question index (0-based)
    $_SESSION['answers'] = [];           // Store our users' answers
    $_SESSION['started'] = true;         // Game initialized flag
}

// Ensure game is properly initialized you can eliminate this nested-if if you want.
if (!isset($_SESSION['started'])) {
    // If no questions loaded, just show Error: triviaQuestions.txt file is empty
    if ($total_questions === 0) {
        // Continue to display error message
    } else {
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    }
}

// Process form submission - POST-REDIRECT-GET pattern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $total_questions > 0) {
    $current = (int) $_SESSION['question_index'];
    
    // Only process if we're still within question range
    if ($current < $total_questions) {
        $user_answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        
        // Validate non-empty answer
        // TODO: At first when I refresh the page, the last answer is saved and it will jump to the next question 
        if ($user_answer === '') {
            // Store error in session and redirect
            $_SESSION['answer_error'] = 'Please type a non-empty answer before submitting.';
        } else {
            // Save answer and advance to next question
            $_SESSION['answers'][$current] = htmlspecialchars($user_answer);
            $_SESSION['question_index'] = $current + 1;
            
            // Clear any previous error
            unset($_SESSION['answer_error']);
        }
    }
    
    // Redirect to prevent form resubmission on refresh
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Retrieve error message from session if exists
$error = '';
if (isset($_SESSION['answer_error'])) {
    $error = $_SESSION['answer_error'];
    unset($_SESSION['answer_error']); // Clear error after displaying
}

// Check if game is finished
$is_finished = isset($_SESSION['question_index']) && $_SESSION['question_index'] >= $total_questions;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Game :: CIS2288 Assignment 5 </title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="trivia.css">
</head>
<body>
    <div id="container">
        <div class="container card p-4">
        <!-- Header with restart button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Online Trivia Game</h3>
            <a href="?restart=1" class="btn btn-outline-secondary btn-sm">Restart</a>
        </div>

        <?php if (isset($file_error) || $total_questions === 0): ?>
            <!-- Error: questions file missing, empty, or unreadable -->
            <div class="alert alert-danger">
                <strong>Error:</strong> <?= isset($file_error) ? htmlspecialchars($file_error) : 'No valid questions found.' ?>
            </div>
            <div class="small text-muted">
                Make sure <code>triviaQuestions.txt</code> exists in the same folder, is readable, 
                and contains properly formatted questions (one per line, question and answer separated by a tab character).
            </div>

        <?php elseif (!$is_finished): 
            // Display current question form
            $index = (int) $_SESSION['question_index'];
            $qText = $questions[$index]['question'];
            $progress_display = "Question " . ($index + 1) . " of $total_questions";
        ?>
            <div class="mb-2 progress-text">
                <?= htmlspecialchars($progress_display) ?>
            </div>

            <div class="card-body p-3 mb-3">
                <div class="question-text mb-3">
                    <!-- <?= str_replace("\n", "<br>", htmlspecialchars($qText)) ?> -->
                     <!-- this is shorter -->
                    <?= nl2br(htmlspecialchars($qText)) ?>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-warning small">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <div class="mb-3">
                        <label for="answer" class="form-label visually-hidden">Your answer</label>
                        <input 
                            id="answer" 
                            name="answer" 
                            type="text" 
                            class="form-control form-control-lg" 
                            placeholder="Type your answer here" 
                            autofocus 
                            autocomplete="off"
                            required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">Submit Answer</button>
                        <a href="?restart=1" class="btn btn-outline-secondary btn-sm">Restart</a>
                    </div>
                </form>
            </div>

            <!-- Progress bar -->
            <div class="mt-2">
                <?php
                    $percent = ($index / max(1, $total_questions)) * 100;
                ?>
                <div class="progress" style="height:10px;">
                    <div 
                        class="progress-bar" 
                        role="progressbar" 
                        style="width: <?= intval($percent) ?>%" 
                        aria-valuenow="<?= intval($percent) ?>" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                    </div>
                </div>
            </div>

        <?php else:
            // Display results page
            $answers = $_SESSION['answers'];
            $correct_count = 0;
        ?>
            <div class="mb-3">
                <h5 class="mb-1">Results</h5>
                <div class="text-muted small">
                    You answered <?= count($answers) ?> of <?= $total_questions ?> questions.
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th>Question</th>
                            <th style="width:20%;">Correct Answer</th>
                            <th style="width:25%;">Your Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $total_questions; $i++):
                            // Get correct answer 
                            $correct = $questions[$i]['answer'];
                            $user = isset($answers[$i]) ? $answers[$i] : '';
                            
                            // Compare case-insensitively after trimming
                            $is_correct = (strcasecmp(trim($user), trim($correct)) === 0);
                            if ($is_correct) $correct_count++;

                            $row_class = $is_correct ? 'table-correct' : 'table-wrong';

                        ?>
                        <?php if ($is_correct): ?>
                            <tr class="table-success">
                        <?php else: ?>
                            <tr class="table-danger">
                        <?php endif; ?>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= nl2br(htmlspecialchars($questions[$i]['question'])) ?></td>
                            <td class="text-center"><?= nl2br(htmlspecialchars($correct)) ?></td>
                            <td class="text-center"><?= nl2br(htmlspecialchars($user)) ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <?php
            // Calculate percentage
            $percent_correct = ($total_questions > 0) 
                ? round(($correct_count / $total_questions) * 100, 1) 
                : 0;
            ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <strong>Score:</strong> <?= $correct_count ?> / <?= $total_questions ?>
                </div>
                <div>
                    <strong>Percentage:</strong> <?= $percent_correct ?>%
                </div>
            </div>

            <!-- TODO: Add "Play Again" button & check the refresh does not load the next question -->
            <div class="mt-3">
                <a href="?restart=1" class="btn btn-primary">Play Again</a>
            </div>

        <?php endif; ?>
    </div>

    </div>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>