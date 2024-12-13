<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 


if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
    header("Location: index.php");
    exit;
}

$job_id = intval($_GET['job_id']); 
$applications = getApplicationsByJobId($pdo, $job_id);


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateStatus'])) {
    $application_id = intval($_POST['application_id']);
    $new_status = trim($_POST['status']);

    if (!empty($application_id) && !empty($new_status)) {
        if (updateApplicationStatus($pdo, $application_id, $new_status)) {
            $message = "<p class='success-message'>Application status updated successfully.</p>";
            $applications = getApplicationsByJobId($pdo, $job_id);
        } else {
            $message = "<p class='error-message'>Failed to update application status.</p>";
        }
    } else {
        $message = "<p class='error-message'>Please provide a valid status.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <link rel="stylesheet" href="styles/applications.css">
</head>
<body>
    <div class="navbar">   
        <?php include 'navbar.php'; ?>
    </div>

    <div class="container">
        <h1>List of Job Applicants</h1>

        <!-- Feedback Message -->
        <?php if (!empty($message)): ?>
            <div class="message-container">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact Number</th>
                <th>Resume</th>
                <th>Application Note</th>
                <th>Status</th>
                <th>Application Date</th>
                <th>Action</th>
            </tr>

            <?php if (!empty($applications)): ?>
                <?php foreach ($applications as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_num']); ?></td>
                    <td>
                        <?php if (!empty($row['resume'])): ?>
                            <a href="../uploads/resumes/<?php echo htmlspecialchars($row['resume']); ?>" target="_blank">View Applicant Resume</a>
                        <?php else: ?>
                            No Resume Uploaded
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['application_note']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['application_date']); ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($row['job_application_id']); ?>">
                            <select name="status" required>
                                <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Accepted" <?php echo $row['status'] === 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                                <option value="Rejected" <?php echo $row['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <button type="submit" name="updateStatus">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">No applications found for this job.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
