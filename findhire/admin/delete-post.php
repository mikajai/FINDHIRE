<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';


if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $deleteQuery = deleteJobPost($pdo, $job_id);

    if ($deleteQuery) {
        $_SESSION['message'] = "Job post deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete job post.";
    }
    header("Location: index.php");
    exit;
}
?>
