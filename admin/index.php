<?php
session_start(); // Start the session

// Check for admin log in
if ($_SESSION['user'] == "") {
pop("No User Is Logged In!");
header('location:login.php');
}

if ($_SESSION['user'] == "Admin") {
//pop("Logged In As Admin");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
session_start();
unset($_SESSION['user']);
header('location:index.php');
}

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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

</head>
<body>
    <center>
        <img src="../img/dnd-project-sm-logo.png">
    </center>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="reports.php">Print Records</a>
        <a href="edit.php">Edit Records</a>
        <a href="admin.php">Add or Edit Person/Reason</a>
        <a href="history.php">Visitor History Data</a>
    </div>

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
            <form method="POST" action="">
    <center>
    <input type="hidden" name="logout" value="logout">
        <button type="submit" class="button">Log Out</button><br>
    </center>
</form>
 
</body>
</html>