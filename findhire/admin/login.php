<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindHire Admin Login Page</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>

<div class="login-container">
    <?php  
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        $status = $_SESSION['status'];
        $message = $_SESSION['message'];
        echo "<h3 style='color: " . ($status == "200" ? "green" : "red") . ";'>$message</h3>";
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    }
    ?>
		
	<h1>FindHire Admin Login Page</h1>
	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="username">Username:</label>
			<input type="text" name="username" required>
		</p>
		<p>
			<label for="password">Password:</label>
			<input type="password" name="password" required>
			<input type="submit" name="loginUserBtn" style="margin-top: 25px;">
		</p>
        
        <!-- for main admin only -->
		<!--<p>Don't have an account? You may register <strong><a href="register.php">here</a></p> --> 
    
	</form>
</div>
</body>
</html>
