<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// ✅ Fetch voters who have voted
$votersQuery = "SELECT username FROM users WHERE has_voted = 1";
$votersResult = $conn->query($votersQuery);

// ✅ Fetch election results (group by candidate name)
$resultsQuery = "
    SELECT 'President' AS position, president AS candidate_name, COUNT(*) as votes FROM votes WHERE president IS NOT NULL GROUP BY president
    UNION
    SELECT 'Vice President' AS position, vice_president AS candidate_name, COUNT(*) as votes FROM votes WHERE vice_president IS NOT NULL GROUP BY vice_president
    UNION
    SELECT 'Secretary' AS position, secretary AS candidate_name, COUNT(*) as votes FROM votes WHERE secretary IS NOT NULL GROUP BY secretary
    UNION
    SELECT 'Treasurer' AS position, treasurer AS candidate_name, COUNT(*) as votes FROM votes WHERE treasurer IS NOT NULL GROUP BY treasurer
    ORDER BY position, votes DESC";

$resultsResult = $conn->query($resultsQuery);

// ✅ Fetch users to allow vote reset
$usersQuery = "SELECT id, username FROM users WHERE has_voted = 1";
$usersResult = $conn->query($usersQuery);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('admin_dashboard_bg.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: auto;
        }

        h2 {
            color: #FFD700;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            color: black;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background: #007BFF;
            color: white;
        }

        .button {
            background: red;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .button:hover {
            background: darkred;
        }

        .logout {
            background: #FF4500;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        
        <h2>✅ Voter List</h2>
        <table>
            <tr>
                <th>Username</th>
            </tr>
            <?php while ($row = $votersResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>🔄 Reset User Votes</h2>
        <form action="reset_vote.php" method="post">
            <select name="user_id">
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="button">Reset Vote</button>
        </form>

        <h2>🏆 Election Results</h2>
        <table>
            <tr>
                <th>Position</th>
                <th>Candidate</th>
                <th>Votes</th>
            </tr>
            <?php while ($row = $resultsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                    <td><?php echo $row['votes']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <h3>Pending User Complaints</h3>
<table border="1">
    <tr>
        <th>User</th>
        <th>Complaint</th>
        <th>Submitted At</th>
        <th>Action</th>
    </tr>

    <?php
    include 'db_connection.php'; // Ensure DB connection is available

    // Fetch pending user complaints
    $result = $conn->query("SELECT contact_requests.id, users.username, contact_requests.message, contact_requests.status, contact_requests.created_at 
                            FROM contact_requests 
                            JOIN users ON contact_requests.user_id = users.id 
                            WHERE status = 'pending' 
                            ORDER BY created_at DESC");

    while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="reset_vote.php?user_id=<?= $row['id'] ?>">Reset Vote</a> | 
                <a href="mark_resolved.php?id=<?= $row['id'] ?>">Mark as Resolved</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>


        <br>
        <a href="logout.php"><button class="button logout">Logout</button></a>
    </div>

</body>
</html>
