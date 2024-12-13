<?php 
require_once 'core/dbConfig.php';
require_once 'core/models.php'; 


if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
	header("Location: admin/index.php");
}


$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FindHire</title>

</head>
<body>
    <div class="navbar">   
        <?php include 'admin/navbar.php'; ?>
    </div>
<br>
<div class="job-posts-container">
    <h2 class="job-posts-title">Message Applicant</h2>
    <div class="job-posts-list">
        <?php 
        $getMessageByApplicationID = getMessageByApplicationID($pdo, $_GET['job_application_id']);
        ?>
        <div class="message-card">
            <?php foreach ($getMessageByApplicationID as $row) { ?>
                <div class="<?php echo ($_SESSION['user_id'] == $row['sender_id']) ? 'sender' : 'receiver'; ?>">
                    <h3 class="sender-name"> <?php echo htmlspecialchars($row['sender_name']); ?></h3>
                    <div class="job-post-details">
                        <p class="job-post-company"> <?php echo htmlspecialchars($row['message_content']); ?></p>
                    </div>
                    <p class="submitted"> <?php echo htmlspecialchars($row['date_sent']); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<section>

    <form class="chatbox" action="core/handleForms.php" method="POST">

            <input type="hidden" name="job_application_id" value="<?php echo isset($_GET['job_application_id']) ? htmlspecialchars($_GET['job_application_id']) : ''; ?>">
            <input type="text" class="message-area" name="message_content" placeholder="Aa" required>
            <input class="chat-btn" type="submit" name="sendMessage" value="Send">

    </form>
   
</section>

<script>
    const messagesContainer = document.querySelector('.message-card');

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    // Automatically scroll to the bottom when the page loads
    scrollToBottom();
</script>

</body>
</html>
