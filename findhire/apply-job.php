<?php  
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);
    $jobDetails = getJobDetails($pdo, $job_id);

    if (!$jobDetails) {
        echo "<h1>Job not found.</h1>";
        exit;
    }
} else {
    echo "<h1>No job selected.</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Page</title>
    <link rel="stylesheet" href="styles/apply-job.css">
</head>
<body>
    <div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>

    <div class="header">
        <h1 style="text-align: center;">Application for: <span><?php echo htmlspecialchars($jobDetails['job_name']); ?></span></h1>
        <p style="text-align: center;">Posted by: <strong><?php echo htmlspecialchars($jobDetails['username']); ?></strong></p>
    </div>

    <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <p>
            <label for="first_name">First Name: </label>
            <input type="text" name="first_name">
        </p>
        <p>
            <label for="last_name">Last Name: </label>
            <input type="text" name="last_name">
        </p>
        <p>
            <label for="contact_num">Contact Number: </label>
            <input type="text" name="contact_num">
        </p>
        <p>
            <label for="resume">Upload your Resume (pdf only): </label>
            <input type="file" name="resume" accept=".pdf">
        </p>
        <p>
            <label for="application_note">Why do you think you are fit for the job?</label><br>
            <textarea name="application_note" rows="5" cols="50"></textarea>
        </p>
        <p>
            <input type="submit" name="applyForJobBtn" value="Submit Application">
        </p>
        <a href="index.php"> Go Back 
    </form>
</body>
</html>
