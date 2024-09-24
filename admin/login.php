<!DOCTYPE html>
<html lang="en">
<head>
<?php

// Include the database configuration file
require_once '../config.php';

// Get the database connection
$conn = getDBConnection();
?>

   <title>Admin Log In</title>
    <link rel="stylesheet" type="text/css" href="theme.php">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const themeSelect = document.getElementById("theme-select");
        const storedTheme = localStorage.getItem("selected_theme");

        // Set the dropdown to the stored theme
        if (storedTheme) {
            themeSelect.value = storedTheme;
        }

        themeSelect.addEventListener("change", function () {
            const selectedTheme = this.value;
            localStorage.setItem("selected_theme", selectedTheme);
            document.getElementById("theme-link").href = `../css/${selectedTheme}.css`;
        });
    });
</script>


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
<?php
session_start(); // Start the session
?>

</head>
  <body>
<?php
//include '../config.php';
session_start(); // Start the session


function pop($msg) {
echo '
<script>
alert("' . $msg . '");
</script>
';
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
    // Get the selected theme from the form
    $selectedTheme = $_POST['theme'];

    // Validate the selected theme (ensure it exists to prevent vulnerabilities)
    $allowedThemes = ['style1', 'darkmode', 'lightmode', 'ltgreen', 'academi', 'gator', 'packers', 'trc'];

    if (in_array($selectedTheme, $allowedThemes)) {
        // Set a session variable with the same key as in JavaScript
        $_SESSION['selected_theme'] = $selectedTheme;
    }
}
?>
  <center>
<?php

    $showForm = true; // Add this variable to control the display

if ($_SESSION['wrong']) {
pop($_SESSION['wrong']);
// remove all session variables
unset($_SESSION['wrong']);
}
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pass'])) {

$conn = getDBConnection();
$sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'";

$result = $conn->query($sql);

//if (mysqli_num_rows($result) > 0) {
if ($result->num_rows > 0) {
$showForm = false;
$_SESSION['email'] = $_POST['email']; 
$_SESSION['pass'] = $_POST['pass'];
check();
}else{
$_SESSION['wrong'] = "User Not Found!";
header('location:login.php');
}
		
}

function check() {
$conn = getDBConnection();
$sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "'";

$result = $conn->query($sql);

WHILE ($row = $result->fetch_assoc()) {
      $admin_pass = $row['pass'];
   if ($admin_pass == $_SESSION['pass']) {
   $_SESSION['user'] = "Admin";
header('location:index.php');
   }else{
$_SESSION['wrong'] = "Incorrect Password!";
header('location:login.php');
   }
  }
}

?>
<br>
    <?php if ($showForm): ?>
    <h2>Admin Log In</h2>
    <form method="POST">
        <table>
            <tr>
                <td><label for="email">Admin Email:</label></td>
                <td><input type="text" name="email" required></td>
            </tr>
            <tr>
                <td><label for="pass">Admin Password:</label></td>
                <td><input type="text" name="pass" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="submit">Log In</button></td>
            </tr>
            <!-- New row for additional instructions -->
            <tr>
                <td colspan="2">
                    <center><b>Fill in the corresponding credentials to log in.</center></b>
                </td>
            </tr>
        </table>
    </form>
    <?php endif; ?>
    <form method="post" action="">
        <label for="theme-select">Select a theme:</label>
        <select id="theme-select" name="theme">
            <option value="style1">Default</option>
            <option value="darkmode">Dark Mode</option>
            <option value="lightmode">Light Mode</option>
            <option value="ltgreen">Light Green Mode</option>
            <option value="academi">Academi Mode</option>
            <option value="gator">Gator Mode</option>
            <option value="packers">Green Bay Mode</option>
            <option value="trc">TRC Mode</option>
            <!-- Add more options for additional themes -->
        </select>
        <input type="submit" value="Apply Theme">
    </form>

    
  </center>
  </body>
</html>