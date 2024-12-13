<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 1) {
	header("Location: admin/index.php");
}

if ($getUserByID['is_suspended'] == 1) {
	header("Location: suspended-account-error.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		body {
			font-family: 'Times New Roman', Times, serif;
			background-color: #f2f2f2;
			margin: 0;
			padding: 0;
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		.navbar {
			margin-top: 20px;
		}

		.navbar a {
			color: #133E87;
		}

		.navbar span {
			color: #133E87;
		}

		.container {
			display: flex;
			justify-content: center;
			margin-top: 20px;
		}

		.userInfo {
			background-color: ghostwhite;
			border: 1px solid gray;
			width: 30%;
			text-align: justify;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		.userInfo h2 {
			color: #000000;
			text-align: center;
		}

		.userInfo h3 {
			color: #000000;
		}

		.userInfo span {
			color: #133E87;
		}
	</style>
</head>
<body>
	<div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>

	<?php $getUserByID = getUserByID($pdo, $_GET['user_id']); ?>
	<div class="userInfo">
		<h2>User Information </h2>
		<h3>Username: <span><?php echo $getUserByID['username']; ?></span></h3>
		<h3>First Name: <span><?php echo $getUserByID['first_name']; ?></span></h3>
		<h3>Last Name: <span><?php echo $getUserByID['last_name']; ?></span></h3>
		<h3>Date Joined: <span><?php echo $getUserByID['date_added']; ?></span></h3>
	</div>
</body>
</html>