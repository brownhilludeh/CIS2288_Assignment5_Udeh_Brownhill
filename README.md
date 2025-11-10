<h1>Assignment 5 – Online Trivia Game</h1>

<p>
For this assignment, you can work <strong>individually</strong> or in <strong>pairs</strong>. 
If you work as a group, submit <strong>one file</strong> and share the same grade. 
Pair Programming is an established practice with many benefits recognized by employers. 
If you work in pairs, take a moment to review the <em>How To Pair Program</em> PDF provided below the requirements. 
Mentioning this experience in an interview can show teamwork and real-world collaboration skills.
</p>

<hr>

<h2>🧠 Objective</h2>
<p>
You will create an <strong>online trivia game</strong> demonstrating your ability to manage user state between page requests using <code>PHP sessions</code>. 
This project focuses on remembering progress and storing user data as the game advances.
</p>

<hr>

<h2>📄 File Required</h2>
<ul>
  <li><strong>trivia.php</strong></li>
</ul>

<hr>

<h2>📝 Requirements</h2>

<h3>1. Trivia Questions</h3>
<ul>
  <li>Load trivia questions from the provided <code>triviaQuestions.zip</code> file.</li>
  <li>Each line in the text file contains one question and answer, separated by a <code>\t</code> (tab) character.</li>
  <li>Your program must handle <strong>any number of questions</strong> from the file.</li>
</ul>

<h3>2. Game Flow</h3>
<ul>
  <li>Display the first question and a text box for the user’s answer.</li>
  <li>The form should <strong>submit back to itself</strong> (<code>trivia.php</code>).</li>
  <li>After a <strong>non-empty</strong> response, move to the next question.</li>
  <li>Show a progress indicator such as <code>Question 3 of 8</code>.</li>
</ul>

<h3>3. End of Game</h3>
<ul>
  <li>When all questions are answered, display the results in an HTML table.</li>
  <li>Each table row should show:
    <ul>
      <li>Question number</li>
      <li>Question text</li>
      <li>Correct answer</li>
      <li>User’s answer</li>
    </ul>
  </li>
  <li>Use background colors:
    <ul>
      <li><span style="background-color:lightgreen;">Light green</span> – Correct answer</li>
      <li><span style="background-color:lightcoral;">Light red</span> – Incorrect answer</li>
    </ul>
  </li>
  <li>Display the <strong>percentage of correct answers</strong> at the end.</li>
</ul>

<h3>4. Restart Option</h3>
<ul>
  <li>Include a <strong>"Restart"</strong> hyperlink.</li>
  <li>This should reset the session, return to the first question, and clear the score.</li>
</ul>

<hr>

<h2>🧩 Technical Details</h2>
<ul>
  <li>Use <strong>sessions</strong> to track user progress and store responses.</li>
  <li>Validate input to ensure responses are not empty.</li>
  <li>Accept answers in <strong>any case</strong> (upper or lower).</li>
  <li>Style your page using <strong>Bootstrap</strong> for:
    <ul>
      <li>Form elements</li>
      <li>Game layout</li>
      <li>Results table</li>
    </ul>
  </li>
</ul>

<hr>

<h2>📦 Submission</h2>
<ul>
  <li>Submit a <code>.zip</code> file containing <strong>trivia.php</strong>.</li>
</ul>

<hr>

<h2>💯 Marking Breakdown (Total: 55 marks)</h2>

<table border="1" cellpadding="8" cellspacing="0">
  <thead>
    <tr>
      <th>Criteria</th>
      <th>Marks</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>Clean code</td><td>/3</td></tr>
    <tr><td>Meaningful comments (header & inline)</td><td>/2</td></tr>
    <tr><td>Bootstrap styling (page, form, results table)</td><td>/5</td></tr>
    <tr><td>Load questions dynamically from text file</td><td>/10</td></tr>
    <tr><td>Data validation (non-empty, case-insensitive)</td><td>/5</td></tr>
    <tr><td>Results table with all details</td><td>/16</td></tr>
    <tr><td>Row colors for correct/incorrect answers</td><td>/5</td></tr>
    <tr><td>Display correct answer percentage</td><td>/4</td></tr>
    <tr><td>Restart hyperlink</td><td>/5</td></tr>
  </tbody>
</table>

<hr>

<h2>🚀 Tips</h2>
<ul>
  <li>Keep your code organized and well-commented.</li>
  <li>Use sessions effectively to maintain progress.</li>
  <li>Test with different numbers of questions.</li>
  <li>Have fun and make your game interactive!</li>
</ul>
