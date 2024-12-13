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

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

if (isset($_POST['backBtn'])) {
    header("Location: ../index.php");
    exit;
}






if (isset($_POST['applyForJobBtn'])) {
    // Get form data
    $job_id = intval($_POST['job_id']);
    $user_id = intval($_POST['user_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $contact_num = trim($_POST['contact_num']);
    $application_note = trim($_POST['application_note']);

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($contact_num)) {
        echo "<p>Please fill out all required fields.</p>";
        exit;
    }

    // Handle resume upload
    if (!empty($_FILES['resume']['name'])) {
        $resume = $_FILES['resume'];
        $allowed_ext = ['pdf'];
        $file_ext = pathinfo($resume['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_ext), $allowed_ext)) {
            echo "<p>Invalid file type. Please upload a PDF.</p>";
            exit;
        }

        $upload_dir = '../uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $resume_name = uniqid('resume_') . '.' . $file_ext;
        $upload_path = $upload_dir . $resume_name;

        if (!move_uploaded_file($resume['tmp_name'], $upload_path)) {
            echo "<p>Failed to upload the resume. Please try again.</p>";
            exit;
        }
    } else {
        echo "<p>Please upload your resume.</p>";
        exit;
    }

    // Save application
    $result = saveJobApplication($pdo, $job_id, $user_id, $first_name, $last_name, $contact_num, $application_note, $resume_name);

    if ($result) {
        echo "<p>Application submitted successfully!</p>";
        header("Location: ../index.php");
        exit;
    } else {
        echo "<p>Failed to submit application. Please try again.</p>";
    }
}


if (isset($_GET['deleteJobApplication'])) {
    $deleteQuery = deleteJobApplication($pdo, $_GET['application_id']);

    if ($deleteQuery) {
        $_SESSION['message'] = "Job application deleted successfully..";
    } else {
        $_SESSION['message'] = "Failed to delete Job application.";
    }
    header("Location: ../delete-application.php");
    exit;
}




// messages

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

    header("Location: ../message-hr.php?job_id=" . $_GET['job_id'] . "&job_application_id=" . $_POST['job_application_id']);
    exit();
}
