<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// get user id 
$user_id = $_GET['user_id'];

// fetch all job applications from the user
$userApplications = getUserApplications($pdo, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link rel="stylesheet" href="styles/made-applications.css">
</head>
<body>
    <div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>
    
    <h1>My Job Applications</h1>

    <div class="applications-container">
        <?php if (!empty($userApplications)): ?>
            <table>
                <tr>
                    <th>Job Name</th>
                    <th>Status</th>
                    <th>Application Date</th>
                </tr>
                <?php foreach ($userApplications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['job_name']); ?></td>
                        <td><?php echo htmlspecialchars($application['status']); ?></td>
                        <td><?php echo htmlspecialchars($application['application_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="no-post">
                <p>You have not submitted any job applications yet.</p>
            </div>
        <?php endif; ?>
        <br>
        <a href="index.php"> Back
    </div>
</body>
</html>
