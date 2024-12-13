CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	is_admin TINYINT(1) NOT NULL DEFAULT 0,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE job_posts (
	job_id INT AUTO_INCREMENT PRIMARY KEY,
	job_name VARCHAR(255),
	job_description VARCHAR(255),
	username VARCHAR(255),
	posted_by INT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE job_application (
	job_application_id INT AUTO_INCREMENT PRIMARY KEY,
	job_id INT,
	user_id INT,
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	contact_num VARCHAR(255),
	application_note TEXT,
	resume VARCHAR(255),
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
	application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    job_application_id INT,
    sender_id INT,
    message_content TEXT,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_application_id) REFERENCES job_application(job_application_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES user_accounts(user_id) ON DELETE CASCADE
);