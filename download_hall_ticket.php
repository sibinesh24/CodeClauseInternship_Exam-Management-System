<?php
session_start();
include 'config.php';

if ($_SESSION['role'] == 'student') {
    $student_id = $_SESSION['user_id'];

    // Query to fetch the latest exam details for the student
    $query = "SELECT exams.exam_name, exams.exam_date, exams.exam_time
              FROM exams
              INNER JOIN student_exams ON exams.id = student_exams.exam_id
              WHERE student_exams.student_id = ?
              ORDER BY student_exams.id DESC
              LIMIT 1";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Generate PDF filename based on exam details
        $pdf_filename = strtolower(str_replace(' ', '_', $row['exam_name'])) . '_' . $row['exam_date'] . '.pdf';
        $pdf_path = 'pdfs/' . $pdf_filename;

        // Check if the PDF file exists
        if (file_exists($pdf_path)) {
            // Set headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $pdf_filename . '"');
            readfile($pdf_path);
            exit;
        } else {
            echo "PDF not found for this exam.";
        }
    } else {
        echo "No exam applied by the student.";
    }
} else {
    echo "Unauthorized access.";
}
?>
