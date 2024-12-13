<?php  
require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $user_id) {
	$sql = "SELECT * FROM user_accounts WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function getAllJobPosts($pdo) {
    $sql = "SELECT job_posts.*, 
				user_accounts.username 
            FROM job_posts 
            JOIN user_accounts ON job_posts.posted_by = user_accounts.user_id
            WHERE user_accounts.is_admin = 1";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        return $stmt->fetchAll();
    }
    return array();
}

function getApplicationFormByID($pdo, $user_id) {
    $query = "SELECT 
                job_application.job_application_id,
                job_posts.job_id
            FROM 
                job_application
            JOIN 
                job_posts ON job_application.job_id = job_posts.job_id
            WHERE 
                job_application.user_id = ?
            ORDER BY 
                job_application.application_date DESC";

    $stmt = $pdo->prepare($query);
    $executeQuery = $stmt->execute([$user_id]);

    if ($executeQuery) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [];
}





function getJobDetails($pdo, $job_id) {
    $sql = "SELECT job_posts.*,
				user_accounts.username 
            FROM job_posts
            JOIN user_accounts ON job_posts.posted_by = user_accounts.user_id 
            WHERE job_posts.job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$job_id]);
    return $stmt->fetch();
}






function saveJobApplication($pdo, $job_id, $user_id, $first_name, $last_name, $contact_num, $application_note, $resume_name) {
    $query = "INSERT INTO job_application (job_id, user_id, first_name, last_name, contact_num, application_note, resume, status)
              VALUES (:job_id, :user_id, :first_name, :last_name, :contact_num, :application_note, :resume, 'Pending')";
    $stmt = $pdo->prepare($query);
    return $stmt->execute([
        ':job_id' => $job_id,
        ':user_id' => $user_id,
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':contact_num' => $contact_num,
        ':application_note' => $application_note,
        ':resume' => $resume_name,
    ]);
}


// Function to fetch applications made by a user
function getUserApplications($pdo, $user_id) {
    $query = "SELECT job_posts.job_name,
              job_application.status,
              job_application.application_date
        FROM job_application
        JOIN job_posts ON job_application.job_id = job_posts.job_id
        WHERE job_application.user_id = :user_id
        ORDER BY job_application.application_date DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateApplicationStatus($pdo, $application_id, $status) {
    $query = "UPDATE job_application SET status = :status WHERE job_application_id = :application_id";
    $stmt = $pdo->prepare($query);
    return $stmt->execute([
        ':status' => $status,
        ':application_id' => $application_id,
    ]);
}





//messages
function insertIntoMessages($pdo, $job_application_id, $sender_id, $message_content) {
    $sql = "INSERT INTO messages (job_application_id, sender_id, message_content) 
            VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);

    $executeQuery = $stmt->execute([$job_application_id, $sender_id, $message_content]);

        if ($executeQuery) {
            return true;
        } 
 
}




function getMessageByApplicationID($pdo, $job_application_id){
    $sql = "SELECT
                messages.message_id,
                messages.job_application_id,
                messages.sender_id,
                messages.message_content,
                messages.date_sent,
                CONCAT(user_accounts.first_name, ' ', user_accounts.last_name) AS sender_name
            FROM 
                messages
            JOIN
                user_accounts ON messages.sender_id = user_accounts.user_id
            WHERE 
                messages.job_application_id = ?"; 
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$job_application_id]); 

    if ($executeQuery) {
        return $stmt->fetchAll(); 
    }

    return [];
}
