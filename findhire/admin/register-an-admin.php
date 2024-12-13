<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$getUserByID = getUserByID($pdo, $_SESSION['user_id']);

if ($getUserByID['is_admin'] == 0) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
		body {
			font-family: 'Times New Roman', Times, serif;
			background-color: #DCE4C9;
		}

        .navbar {
            margin-top: 20px;
        }

        .navbar a, .navbar span {
            color: #436850;
        }

        .centered-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .form-container {
            background-color: ghostwhite;
            border: 1px solid gray;
            width: 50%;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-top: 8px;
        }

        .form-container .warning {
            background-color: #FFB6C1;
            padding: 15px;
            color: red;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-container form p {
            margin-bottom: 15px;
        }

        .form-container form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container form input[type="text"],
        .form-container form input[type="password"] {
            width: 97%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container form input[type="submit"] {
            background-color: #436850;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
			margin-left: 545px;
        }

        .form-container form input[type="submit"]:hover {
            background-color: #36593c;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php include 'navbar.php'; ?>
    </div>

    <div class="centered-container">
        <div class="form-container">
            <?php if (isset($_SESSION['message'], $_SESSION['status'])): ?>
                <div class="message <?php echo $_SESSION['status'] === '200' ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['status']); ?>
            <?php endif; ?>

            <h1>Create an Admin Account</h1>
            <div class="warning">Please take note of the admin's password</div>

            <form action="core/handleForms.php" method="POST">
                <p>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </p>
                <p>
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required>
                </p>
                <p>
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required>
                </p>
                <p>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </p>
                <p>
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </p>
                <p>
                    <input type="submit" name="insertNewAdminBtn" value="Create Admin">
                </p>
            </form>
        </div>
    </div>
</body>
</html>
