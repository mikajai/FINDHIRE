<div class="navbar" style="text-align: center; margin-bottom: 50px;">

	<h1>Welcome to FindHire, <span><?php echo $_SESSION['username']; ?></span></h1>
	<a href="index.php">Home </a> |
	<a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">Your Profile </a> |
	<a href="alladmins.php">All Admins </a> |
	<a href="register-an-admin.php">Register An Admin </a> |
	<a href="core/handleForms.php?logoutUserBtn=1">Logout</a>
	
</div>