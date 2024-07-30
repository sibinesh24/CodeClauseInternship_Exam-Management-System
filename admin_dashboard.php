<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Handle form submissions for adding exams and student groups
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_exam'])) {
        $exam_name = $_POST['exam_name'];
        $exam_date = $_POST['exam_date'];
        $exam_time = $_POST['exam_time'];

        $stmt = $conn->prepare("INSERT INTO exams (exam_name, exam_date, exam_time) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $exam_name, $exam_date, $exam_time);
        $stmt->execute();
    } elseif (isset($_POST['add_group'])) {
        $group_name = $_POST['group_name'];

        $stmt = $conn->prepare("INSERT INTO student_groups (group_name) VALUES (?)");
        $stmt->bind_param("s", $group_name);
        $stmt->execute();
    }
}

// Fetch existing exams and student groups
$exams_result = $conn->query("SELECT * FROM exams");
$groups_result = $conn->query("SELECT * FROM student_groups");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            background: linear-gradient(135deg, #ff6f61, #d4a5a5, #7acbd4, #42b983);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
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
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], input[type="date"], input[type="time"] {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="time"]:focus {
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
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin</h1>
    
    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>

    <h2>Add Exam</h2>
    <form method="post">
        <input type="text" name="exam_name" placeholder="Exam Name" required>
        <input type="date" name="exam_date" required>
        <input type="time" name="exam_time" required>
        <button type="submit" name="add_exam">Add Exam</button>
    </form>

    <h2>Add Student Group</h2>
    <form method="post">
        <input type="text" name="group_name" placeholder="Group Name" required>
        <button type="submit" name="add_group">Add Group</button>
    </form>

    <h2>Existing Exams</h2>
    <ul>
        <?php while ($row = $exams_result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['exam_name']); ?> - <?php echo htmlspecialchars($row['exam_date']); ?> - <?php echo htmlspecialchars($row['exam_time']); ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Existing Student Groups</h2>
    <ul>
        <?php while ($row = $groups_result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($row['group_name']); ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
