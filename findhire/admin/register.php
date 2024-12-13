<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/register.css">
</head>
<body>
<div class="register-container">
	<?php  
	if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

		if ($_SESSION['status'] == "200") {
			echo "<p style='color: green;'>{$_SESSION['message']}</p>";
		}

		else {
			echo "<p style='color: red;'>{$_SESSION['message']}</p>";	
		}

	}
	unset($_SESSION['message']);
	unset($_SESSION['status']);
	?>
    
	<h1>Register here!</h1>
	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="username">Username:</label>
			<input type="text" name="username">
		</p>
		<p>
			<label for="username">First Name:</label>
			<input type="text" name="first_name">
		</p>
		<p>
			<label for="username">Last Name:</label>
			<input type="text" name="last_name">
		</p>
		<p>
			<label for="username">Password:</label>
			<input type="password" name="password">
		</p>
		<p>
			<label for="username">Confirm Password:</label>
			<input type="password" name="confirm_password">
			<input type="submit" name="insertNewUserBtn" style="margin-top: 25px;">
		</p>
	</form>
</div>
</body>
</html>