<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
	header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="../styles/styles.css">
</head>
<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        background-color: #DCE4C9;
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
        color: #436850;
    }

    .navbar span {
        color: #436850;
    }

    .allUsers {
        background-color: ghostwhite;
        border: 1px solid gray;
        width: 300px;
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    a {
        text-decoration: none;
        color: #12372A;
    }

    a:hover {
        text-decoration: underline;
    }

</style>
<body>

	<div class= "navbar">
        <?php include 'navbar.php'; ?>
    </div>

	<div class="container" style="display: flex; justify-content: center;">
		<div class="allUsers">
			<h1>All Admins</h1>
			<ul style="display: flex; flex-direction: column; align-items: center; list-style-type: disc; padding: 0;">
				<?php $getAllAdmins = getAllAdmins($pdo); ?>
				<?php foreach ($getAllAdmins as $row) { ?>
					<li style="margin-top: 10px;"><a href="profile.php?user_id=<?php echo $row['user_id']; ?>"><?php echo $row['username']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</body>
</html>