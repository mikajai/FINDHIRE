<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}


if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_POST['insertNewAdminBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$is_admin = true;

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../alladmins.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register-an-admin.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register-an-admin.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register-an-admin.php");
	}
}


if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}


if (isset($_POST['insertJobPostBtn'])) {
    $title = trim($_POST['job_name']);
    $description = trim($_POST['job_description']);
    $posted_by = $_SESSION['user_id']; // Use logged-in user's ID.

    if (!empty($title) && !empty($description)) {
        $insertJobPostQuery = insertJobPost($pdo, $title, $description, $posted_by);
        $_SESSION['jobPostMessage'] = $insertJobPostQuery ? "Job post added successfully!" : "Failed to add job post.";
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['jobPostMessage'] = "Please fill all fields.";
        header("Location: ../index.php");
        exit;
    }
}


if (isset($_GET['deleteJobPost'])) {
    $job_id = $_GET['deleteJobPost'];
    $deleteQuery = deleteJobPost($pdo, $job_id);

    if ($deleteQuery) {
        $_SESSION['message'] = "Job post deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete job post.";
    }
    header("Location: ../index.php");
    exit;
}



if (isset($_POST['sendMessage'])) {

    
    $job_application_id = $_POST['job_application_id'];
    $sender_id = $_SESSION['user_id'] ?? null; // Validate session
    $message_content = $_POST['message_content'] ?? null;

    // Validate sender_id and message_content
    if (!$sender_id) {
        die("Error: User is not logged in.");
    }
    if (!$message_content) {
        die("Error: Message content cannot be empty.");
    }

    // Check if form_id exists in the database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM job_application WHERE job_application_id = ?");
    $stmt->execute([$job_application_id]);
    if ($stmt->fetchColumn() == 0) {
        die("Error: Invalid job_application_id. No such job_application_id exists in application_form.");
    }

    // Insert the message
    $response = insertIntoMessages($pdo, $job_application_id, $sender_id, $message_content);

    // Handle response
    $_SESSION['message'] = $response['message'] ?? "Message sent!";
    $_SESSION['statusCode'] = $response['statusCode'] ?? 200;

    header("Location: ../message-applicant.php?job_id=" . $_GET['job_id'] . "&job_application_id=" . $_POST['job_application_id']);
    exit();
}
