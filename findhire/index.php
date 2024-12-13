<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
    header("Location: admin/index.php");
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
    <title>Job Posts</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>

    <h2 style="text-align: center;">See all available jobs below!</h2>
    <?php if (!empty($getAllJobPosts)) : ?>
        <?php foreach ($getAllJobPosts as $post) : ?>
            <div class="jobs-container">
                <h2>Job Title: <?php echo htmlspecialchars($post['job_name']); ?></h2>
                <p><strong>Job Description: </strong><?php echo htmlspecialchars($post['job_description']); ?></p>
                    <br><p><em>Posted by: <?php echo htmlspecialchars($post['username']); ?></em></p>
                    <p><em>Posted on: <?php echo htmlspecialchars($post['date_added']); ?></em></p>
            
                <div class="links">
                <a href="apply-job.php?job_id=<?php echo $post['job_id']; ?>">Apply for this Job</a> |
                <a href="message-hr.php?job_id=<?php echo $job_id; ?>&job_application_id=<?php echo $job_application_id; ?>">Send a Message</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="no-post">
            <p>No job posts available for now.</p>
        </div>
    <?php endif; ?>
</body>
</html>
