<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Handle form submission for applying for exams
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['exam_id'])) {
    $exam_id = $_POST['exam_id'];
    $student_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO student_exams (student_id, exam_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $exam_id);
    $stmt->execute();
}

// Fetch available exams
$exams_result = $conn->query("SELECT * FROM exams");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fad0c4, #a1c4fd, #c2e9fb, #c2e9fb, #d4fc79, #96e6a1);
            background-size: 300% 300%;
            animation: gradientBG 15s ease infinite;
            color: #fff;
            padding: 20px;
            margin: 0;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        h1, h2 {
            color: #333;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.3);
        }

        form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        input[type="text"], input[type="date"], input[type="time"], select {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="time"]:focus, select:focus {
            border-color: #42b983;
            outline: none;
        }

        button {
            padding: 15px;
            background-color: #42b983;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #36a473;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Welcome, Student</h1>

    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>

    <h2>Apply for Exam</h2>
    <form method="post">
        <select name="exam_id" required>
            <?php while ($row = $exams_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['exam_name']); ?> - <?php echo htmlspecialchars($row['exam_date']); ?> - <?php echo htmlspecialchars($row['exam_time']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Apply</button>
    </form>

    <h2>Download Hall Ticket</h2>
    <form method="post" action="download_hall_ticket.php">
        <button type="submit">Download Hall Ticket</button>
    </form>
</body>
</html>
