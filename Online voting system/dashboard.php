<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$servername = "localhost";
$db_username = "root";
$db_password = "Drawing4000";
$dbname = "user_login";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has already submitted their vote
$query = "SELECT submitted FROM votes WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$vote = $result->fetch_assoc();
$hasVoted = $vote && $vote['submitted'];

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote for Your Leaders</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes moneyAnimation {
            0%, 100% { color: gold; transform: scale(1); }
            50% { color: green; transform: scale(1.1); }
        }

        body {
            background: url('voter.jpeg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            animation: fadeIn 1s ease-in-out;
        }

        .candidate {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            color: black;
            font-weight: bold;
        }

        .candidate img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .vote-header {
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 5px black;
            animation: bounce 2s infinite;
        }

        .president-header {
            color: gold;
            font-style: italic;
        }

        .vice-president-header {
            color: lightblue;
            font-weight: bold;
            text-decoration: underline;
        }

        .secretary-header {
            color: violet;
            font-family: 'Courier New', monospace;
        }

        .treasurer-header {
            color: green;
            animation: moneyAnimation 2s infinite;
        }

        .president-message {
    color: gold;
    font-weight: bold;
}

.vice-president-message {
    color: lightblue;
    font-weight: bold;
}

.secretary-message {
    color: violet;
    font-style: italic;
}

.treasurer-message {
    color: green;
    font-weight: bold;
    animation: moneyAnimation 2s infinite;
}
        .submit-btn {
    padding: 16px 30px; /* Increased padding for a bigger button */
    background-color: green;
    color: white;
    font-size: 22px; /* Increased font size */
    border: none;
    cursor: pointer;
    border-radius: 8px; /* Slightly rounded corners */
    width: 60%; /* Adjust the width to make it wider */
    max-width: 300px; /* Prevent it from becoming too big on larger screens */
    display: block;
    margin: 20px auto; /* Center the button */
}


        .submit-btn:disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        input[type="checkbox"] {
            width: 25px;
            height: 25px;
            appearance: none;
            background-color: white;
            border: 2px solid red;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
        }

        input[type="checkbox"]:checked {
            background-color: red;
        }

        input[type="checkbox"]:checked::after {
            content: '✔';
            font-size: 20px;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .welcome-message {
            font-size: 36px;
            font-weight: bold;
            color: yellow;
            text-shadow: 2px 2px 5px black;
            animation: fadeIn 2s ease-in-out;
        }

        .vote-message {
            font-size: 20px;
            font-weight: bold;
            color: red;
            animation: bounce 2s infinite;
        }

        .side-message {
            font-size: 42px;
            font-style: italic;
            color: green;
            width: 20%;
            position: absolute;
            top: 90%;
        }

        .left-message {
            left: 5%;
            animation: fadeIn 2s ease-in-out;
        }

        .right-message {
            right: 5%;
            animation: bounce 2s infinite;
        }
        .rules-container {
    background-color: rgba(255, 0, 0, 0.1); /* Light red background */
    border: 2px solid red;
    padding: 15px;
    width: 80%;
    margin: 20px auto;
    color: red;
    font-weight: bold;
    text-align: left;
    font-size: 18px;
}

.rules-container h2 {
    text-align: center;
    color: darkred;
    font-size: 30px;
    text-decoration: underline;
}

.rules-container ol {
    padding-left: 20px;
}
.logout-btn, .review-btn {
    display: inline-block;
    padding: 12px 20px;
    font-size: 18px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    text-decoration: none;
    margin: 10px 5px; /* Add space between buttons */
}

.logout-btn {
    background-color: red;
    color: white;
}

.logout-btn:hover {
    background-color: darkred;
}

.review-btn {
    background-color: blue;
    color: white;
}

.review-btn:hover {
    background-color: darkblue;
}



    </style>

    <script>
        function enforceSingleSelection(category) {
            const checkboxes = document.querySelectorAll(`input[name="${category}"]`);
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    checkboxes.forEach(box => {
                        if (box !== this) {
                            box.checked = false;
                        }
                    });
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            enforceSingleSelection('president');
            enforceSingleSelection('vice_president');
            enforceSingleSelection('secretary');
            enforceSingleSelection('treasurer');
        });
    </script>
</head>
<body>
<?php if (isset($_GET['success'])): ?>
    <p class="success-message">✅ Thank you! Your vote has been successfully submitted.</p>
<?php endif; ?>

    <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <div class="rules-container">
        <h2>Voting Rules</h2>
        <p> Please Read the following rules carefully before casting your vote.</p>
        <ol>
            <li>Only registered users are allowed to vote. Each voter must log in before voting.</li>
            <li>One person, one vote – A user can vote only once. No duplicate voting.</li>
            <li>You can select only one candidate for each position.</li>
            <li>Votes can be modified before submission, but once submitted, they are final.</li>
            <li>Votes are private and cannot be viewed by other users.</li>
            <li>Admins can manage candidates and monitor votes but cannot alter submitted votes.</li>
            <li>Voting must be completed within the set time frame. Late votes will not count.</li>
            <li>Voters must make their choices independently without coercion or manipulation.</li>
            <li>Any attempt to hack or manipulate the voting system will lead to disqualification.</li>
            <li>Once voting is closed and results are generated, they are final.</li>
        </ol>
    </div>
    <p class="vote-message">Cast your votes now!</p>

    <div class="side-message left-message">"Voting is a crucial democratic process that allows individuals to choose their leaders and influence policies that shape society. It ensures fair representation, strengthens democracy, and upholds the principle of "one person, one vote."  Various voting systems exist, such as First-Past-the-Post, Proportional Representation, and Ranked Choice Voting, each impacting election outcomes differently. The process involves registration, verification, casting the vote, and counting results.</div>
    <div class="side-message right-message">"A leader is chosen by those who participate. Be involved! Your vote is your voice. Make it count!"</div>

    <div class="container">
        <h2 class="vote-header president-header">President</h2>
        <h4 class="president-message">A President is not just a leader but a visionary. They guide, inspire, and shape the future. Choose wisely, for your vote determines the direction of progress!</h4>
        <form id="voteForm" action="submit_vote.php" method="POST">
            <div class="candidate">
                <img src="candidate2man.jpeg" alt="Candidate 1">
                <label><input type="checkbox" name="president" value="Julius Kingori" <?php echo $hasVoted ? 'disabled' : ''; ?>> Julius Kingori</label>
            </div>
            <div class="candidate">
                <img src="candidate10wom.jpeg" alt="Candidate 2">
                <label><input type="checkbox" name="president" value="Evlyne Maina" <?php echo $hasVoted ? 'disabled' : ''; ?>> Evlyne Maina</label>
            </div>
            <div class="candidate">
                <img src="candidate5man.jpeg" alt="Candidate 3">
                <label><input type="checkbox" name="president" value="John Kinyanjui" <?php echo $hasVoted ? 'disabled' : ''; ?>> John Kinyanjui</label>
            </div>

            <h2 class="vote-header vice-president-header">Vice President</h2>
            <h4 class="vice-president-message">A Vice President is the backbone of leadership, standing ready to support, lead, and serve. Choose a deputy who strengthens the mission and empowers the future!</h4>
            <div class="candidate">
                <img src="candidate3wom.jpeg" alt="Candidate 1">
                <label><input type="checkbox" name="vice_president" value="Kira Leptang" <?php echo $hasVoted ? 'disabled' : ''; ?>> Kira Leptang</label>
            </div>
            <div class="candidate">
                <img src="candidate1wom.jpeg" alt="Candidate 2">
                <label><input type="checkbox" name="vice_president" value="Kena Wankiku" <?php echo $hasVoted ? 'disabled' : ''; ?>> Kena Wankiku</label>
            </div>

            <h2 class="vote-header secretary-header">Secretary</h2>
            <h4 class="secretary-message">The Secretary is the guardian of records and the voice of organization. A leader who ensures clarity, accountability, and smooth communication. Choose wisely!</h4>
            <div class="candidate">
                <img src="candidate6man.jpeg" alt="Candidate 1">
                <label><input type="checkbox" name="secretary" value="Jose Kipkingori" <?php echo $hasVoted ? 'disabled' : ''; ?>> Jose Kipkingori</label>
            </div>
            <div class="candidate">
                <img src="candidate9wom.jpeg" alt="Candidate 2">
                <label><input type="checkbox" name="secretary" value="Maria Kari" <?php echo $hasVoted ? 'disabled' : ''; ?>> Maria Kari</label>
            </div>
            

            <h2 class="vote-header treasurer-header">Treasurer</h2>
            <h4 class="treasurer-message">The Treasurer is the backbone of financial integrity, ensuring every resource is managed wisely. A leader you can trust with transparency and accountability. Choose the right steward!</h4>
            <div class="candidate">
                <img src="candidate7man.jpeg" alt="Candidate 1">
                <label><input type="checkbox" name="treasurer" value="Kingsly Jethro" <?php echo $hasVoted ? 'disabled' : ''; ?>> Kingsly Jethro</label>
            </div>
            <div class="candidate">
                <img src="candidate4wom.jpeg" alt="Candidate 2">
                <label><input type="checkbox" name="treasurer" value="Talia Aghul" <?php echo $hasVoted ? 'disabled' : ''; ?>> Talia Aghul</label>
            </div>
            <div class="candidate">
                <img src="candidate8man.jpeg" alt="Candidate 3">
                <label><input type="checkbox" name="treasurer" value="Kevin Kiplangat" <?php echo $hasVoted ? 'disabled' : ''; ?>> Kevin Kiplangat</label>
            </div>

            <button type="submit" class="submit-btn" <?php echo $hasVoted ? 'disabled' : ''; ?>>Submit Vote</button>
            <a href="logout.php" class="logout-btn">Logout</a>
            <a href="review_vote.php" class="review-btn">Review Your Vote</a>
        </form>
        <!-- Contact Admin Section -->
<h3>Need Help? Contact Admin</h3>
<form method="POST">
    <textarea name="message" placeholder="Explain your issue..." required style="width: 100%; height: 100px;"></textarea><br>
    <button type="submit" name="submit_request">Submit Request</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    include 'db_connection.php'; // Ensure the database connection is included
    
    if (!isset($_SESSION['user_id'])) {
        echo "<p style='color: red;'>You must be logged in to submit a request.</p>";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_requests (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Your request has been submitted.</p>";
        } else {
            echo "<p style='color: red;'>Error submitting request.</p>";
        }
    } else {
        echo "<p style='color: red;'>Message cannot be empty.</p>";
    }
}
?>

    </div>
</body>
</html>
