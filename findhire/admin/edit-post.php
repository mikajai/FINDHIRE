<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username']) || !isset($_GET['job_id'])) {
    header("Location: index.php");
    exit;
}

$job_id = $_GET['job_id'];
$jobPost = getJobPostById($pdo, $job_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_name = trim($_POST['job_name']);
    $job_description = trim($_POST['job_description']);

    if (!empty($job_name) && !empty($job_description)) {
        $updateStatus = updateJobPost($pdo, $job_id, $job_name, $job_description);
        if ($updateStatus) {
            $_SESSION['message'] = "Job post updated successfully.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['message'] = "Failed to update job post.";
        }
    } else {
        $_SESSION['message'] = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
    <link rel="stylesheet" href="styles/edit-post.css">
</head>
<body>
    <div class="edit-post">
        <h2>Edit Job Post</h2>
        <form method="POST">
            <p>
                <label for="job_name">Job Title:</label>
                <input type="text" name="job_name" value="<?php echo htmlspecialchars($jobPost['job_name']); ?>" required>
            </p>
            <p>
                <label for="job_description">Job Description:</label>
                <textarea name="job_description" required><?php echo htmlspecialchars($jobPost['job_description']); ?></textarea>
            </p>
            <p>
                <input type="submit" value="Update Job Post">
            </p>
        </form>
    </div>
</body>
</html>
