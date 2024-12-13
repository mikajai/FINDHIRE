<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin/login.php");
    exit;
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
    header("Location: ../index.php");
    exit;
}

$getAllJobPosts = getAllJobPosts($pdo); // to fetch all job posts
$getApplicationFormByID = getApplicationFormByID($pdo, $_SESSION['user_id']);

if (!empty($getApplicationFormByID)) {
    $applicationDetails = $getApplicationFormByID[0]; // Assuming it returns an array of data
    $job_id = $applicationDetails['job_id'];
    $job_application_id = $applicationDetails['job_application_id'];
} else {
    $job_id = null;
    $job_application_id = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    
    <div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>
    
    <form action="core/handleForms.php" method="POST">
        <p>
            <label for="job_name">Job Title:</label>
            <input type="text" name="job_name" required>
        </p>
        <p>
            <label for="job_description">Job Description:</label>
            <textarea name="job_description" rows="5" cols="50" required></textarea>
        </p>
        <p>
            <input type="submit" name="insertJobPostBtn" value="Submit Job Post">
        </p>
    </form>

    
    <div class="container">
        <h2>HERE ARE ALL THE AVAILABLE JOB APPLICATIONS</h2>
        <?php if (!empty($getAllJobPosts)) : ?>
            <?php foreach ($getAllJobPosts as $post) : ?>
                <div class="jobs">
                    <h3>Job Title: <?php echo htmlspecialchars($post['job_name']); ?></h3>
                    <p><strong>Job Description: </strong><?php echo htmlspecialchars($post['job_description']); ?></p>
                    <br><p><em>Posted by: <?php echo htmlspecialchars($post['username']); ?> | HR Admin </em></p>
                    <p><em>Posted on: <?php echo htmlspecialchars($post['date_added']); ?></em></p>
                        <div class="actions">
                            <a href="applications.php?job_id=<?php echo $post['job_id']; ?>">View Applicants</a> | 
                            <a href="message-applicant.php?job_id=<?php echo $post['job_id']; ?>&job_application_id=<?php echo $job_application_id; ?>">Send Message</a>  |
                            <a href="edit-post.php?job_id=<?php echo $post['job_id']; ?>">Edit Post</a> | 
                            <a href="delete-post.php?job_id=<?php echo $post['job_id']; ?>">Delete</a>
                        </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="no-post">
                <p>You have not posted anything.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
