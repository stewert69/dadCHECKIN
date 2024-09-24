<!DOCTYPE html>
<html>
<head>
    <title>Database Installation</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <style>
        table {
            width: 60%;
            margin: 0 auto; /* Center the table */
        }
        .info-table {
            width: 60%;
            margin: 0 auto; /* Center the table */
            background-color: transparent;
            color: white;
        }
        .notice {
            color: #EB3D6A;
        }
    </style>
</head>
<body>
    <?php
    $showForm = true; // Add this variable to control the display

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start(); // Start the session
        $_SESSION['servername'] = $servername = $_POST["servername"];
        $_SESSION['username'] = $username = $_POST["username"];
        $_SESSION['password'] = $password = $_POST["password"];
        $_SESSION['dbname'] = $dbname = $_POST["dbname"];
		$admin_f_name = $_POST["admin_f_name"];
		$admin_l_name = $_POST["admin_l_name"];
		$admin_pass = $_POST["admin_pass"];
		$admin_email = $_POST["admin_email"];

        // Create the database connection
        $conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create the database
        $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($conn->query($sql_create_db) === TRUE) {
            echo "Database created successfully.<br>";

            // Connect to the new database
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Define SQL commands to create tables
            $sql = array(
                "CREATE TABLE IF NOT EXISTS users (
				    id INT(8) AUTO_INCREMENT PRIMARY KEY,
					f_name VARCHAR(32) NOT NULL,
					l_name VARCHAR(32) NOT NULL,
					email VARCHAR(128) NOT NULL,
					pass VARCHAR(64) NOT NULL
                )",
                "CREATE TABLE IF NOT EXISTS visit_reasons (
                    reason_id INT AUTO_INCREMENT PRIMARY KEY,
                    reason_description VARCHAR(255) NOT NULL
                )",
                "CREATE TABLE IF NOT EXISTS visiting_persons (
                    person_id INT AUTO_INCREMENT PRIMARY KEY,
                    person_name VARCHAR(255) NOT NULL
                )",
                "CREATE TABLE IF NOT EXISTS visitors (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    checkin_time DATETIME NOT NULL,
                    checkout_time DATETIME,
                    first_name VARCHAR(255),
                    last_name VARCHAR(255),
                    phone VARCHAR(20),
                    email VARCHAR(255),
                    visiting_person_id INT,
                    visit_reason_id INT,
                    FOREIGN KEY (visiting_person_id) REFERENCES visiting_persons(person_id),
                    FOREIGN KEY (visit_reason_id) REFERENCES visit_reasons(reason_id)
                )",
				"INSERT INTO users 
				    (f_name, l_name, pass, email)
				    VALUES ('".$admin_f_name."', '".$admin_l_name."', '".$admin_pass."', '".$admin_email."'
				)"
            );

            // Execute SQL commands
            foreach ($sql as $query) {
                if ($conn->query($query) === TRUE) {
                    echo "Table created successfully, Or Data Has Been Inserted<br>";
                } else {
                    echo "Error creating table: " . $conn->error . "<br>";
                }
            }

            // Close the connection
            $conn->close();

            // Create config.php file
            $config_content = '<?php
// config.php
function getDBConnection() {

    $db_server = "' . $servername . '";
    $db_username = "' . $username . '";
    $db_password = "' . $password . '";
    $db_database = "' . $dbname . '";

    $conn = new mysqli($db_server, $db_username, $db_password, $db_database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>';

            // Write content to config.php
            $config_file = '../config.php';
            file_put_contents($config_file, $config_content);

            echo "config.php created successfully.<br>";

            // Set $showForm to false to hide the form
            $showForm = false;
        } else {
            echo "Error creating database: " . $conn->error;
        }
    }
    ?>

    <?php if ($showForm): ?>
    <h2>Database Installation</h2>
    <form method="POST">
        <table>
            <tr>
                <td><label for="servername">DB Server Name:</label></td>
                <td><input type="text" name="servername" required value="localhost"></td>
            </tr>
            <tr>
                <td><label for="username">DB Username:</label></td>
                <td><input type="text" name="username" required></td>
            </tr>
            <tr>
                <td><label for="password">DB Password:</label></td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td><label for="dbname">Database Name:</label></td>
                <td><input type="text" name="dbname" required></td>
            </tr>
            <tr>
                <td><label for="dbname">Admin First Name:</label></td>
                <td><input type="text" name="admin_f_name" required></td>
            </tr>
            <tr>
                <td><label for="dbname">Admin Last Name:</label></td>
                <td><input type="text" name="admin_l_name" required></td>
            </tr>
            <tr>
                <td><label for="dbname">Admin Password:</label></td>
                <td><input type="text" name="admin_pass" required></td>
            </tr>
            <tr>
                <td><label for="dbname">Admin Email:</label></td>
                <td><input type="text" name="admin_email" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="submit">Install</button></td>
            </tr>
            <!-- New row for additional instructions -->
            <tr>
                <td colspan="2">
                    <center><b>Fill in the corresponding credentials you have set for your database.</center></b>
                </td>
            </tr>
        </table>
    </form>
    <?php endif; ?>

    <?php if (!$showForm): ?>
    <table class="info-table">
        <tr>
            <td colspan="2">
                <center><h2 class="notice">NOTICE:</h2></center>
            </td>
        </tr>
        <tr>
            <td>
              <center>  Be sure to delete the install/ directory to better protect your website.</center>
            </td>
        </tr>
        <tr>
            <td>
                <b>To begin using the software, and configure the database sections that are located in the admin panel, click the button below.
		<p>You will need to configure the persons Visited and reasons for the visit. You will do that in the Add Person/Reason section in the Admin Panel.</b>
		</b>
            </td>
        </tr>
    </table>
    <h2><a href="/admin/login.php" class="button">Log In to Continue to the Admin Dashboard to Finish Configuration.</a></h2>
    <?php endif; ?>
</body>
</html>
