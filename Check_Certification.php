<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

// Conneting To Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";
$URN = $_SESSION['urn'];

// Create A Connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_application'])) {
    $deleteQuery = "DELETE FROM `student-registeration` WHERE `urn` = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "s", $URN);
    if (mysqli_stmt_execute($deleteStmt)) {
        echo '<div class="container my-3"><div class="alert alert-success" role="alert">
                Your application has been successfully deleted. You can now submit a new one.
              </div></div>';
    } else {
        echo '<div class="container my-3"><div class="alert alert-danger" role="alert">
                Failed to delete the application. Please try again.
              </div></div>';
    }
}


// Prepare the statement
$query = "SELECT `status`, `current_stage`, `rejection_comment`, `file_path` FROM `student-registeration` WHERE `urn` = ?";



$stmt = mysqli_prepare($conn, $query);

// Bind the parameters
mysqli_stmt_bind_param($stmt, "s", $URN);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">

    <title>Welcome - <?php echo $_SESSION['urn'] ?></title>
</head>
<body>
<?php require 'partials/_nav.php' ?>

<?php
// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <div class="container my-3">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Welcome Back - <?php echo $_SESSION['urn'] ?></h4>
                <p> You are logged in as <?php echo $_SESSION['urn'] ?>. You successfully filled the registration
                    form.</p>
                <hr>
                <p class="mb-0">Whenever you need to, be sure to logout <a href="/NDA/logout.php"> using this
                        link.</a></p>
            </div>
        </div>

        <?php
        // Display status based on database value
        $status = $row['status'];
        $current_stage = $row['current_stage'];
        $file_path = $row['file_path']; 
        


        if ($status == "approved") {
            echo '<div class="container my-3"> <div class="alert alert-success" role="alert">';
            echo '<h4 class="alert-heading">Congratulations!</h4>';
            echo '<p> Your Certificate is Approved </p> </p>';
            echo '<hr>';
            $full_file_path = "../NDA/teacher/" . $file_path; // Adjust if needed
            if (!empty($file_path) && file_exists($full_file_path)) {
                echo '<p class="mb-0"> You can download your certificate <a href="' . $full_file_path . '" target="_blank">using this link.</a></p>';
            } else {
                echo '<p class="mb-0 text-danger">Certificate file not found.</p>';
            }

            echo '</div>';
            echo '</div>';
        } elseif ($status == "rejected") {
            echo '<div class="container my-3"> <div class="alert alert-danger" role="alert">';
            echo '<h4 class="alert-heading">Rejected</h4>';
            echo '<p> Your NDA form is rejected. </p> </p>';
            echo '<p><strong>Rejection Reason:</strong>' .htmlspecialchars($row['rejection_comment']).'</p>';
            echo '<hr>';
            echo '<p class="mb-0">Please Visit Your Department!</p>';
            echo '</div>';
            echo '</div>';
        } elseif ($status == "pending") {
            echo '<div class="container my-3"> <div class="alert alert-warning" role="alert">';
            echo '<h4 class="alert-heading"> Pending ! </h4>';
            echo '<p> Your Certificate is in Pending List. please wait for some time. </p> </p>';
            echo '<hr>';
            echo '<p class="mb-0"><small> You can also contact your branch offices </small> </p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="container my-3">';
            echo '<div class="alert alert-info" role="alert">';
            echo '<h5>Current Stage of Your Application:</h5>';
            echo '<p>' . htmlspecialchars($current_stage) . '</p>';
            echo '</div>';
            echo '</div>';

        }
        echo '<form method="POST" action="">';
        echo '<div class="container my-3">';
        echo '<button type="submit" name="delete_application" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete your application?\')">Delete Application</button>';
        echo '</div>';
        echo '</form>';

    }
} else {
  echo '<div class="container my-3"> <div class="alert alert-danger" role="alert">';
  echo '<h4 class="alert-heading">No Records Found</h4>';
  echo '<p> You do not filled the registration form yet. </p> </p>';
  echo '<hr>';
  echo '<p class="mb-0">Please fill the registration form first!</p>';
  echo '</div>';
  echo '</div>';
}

// Close the connection
mysqli_close($conn);
?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>
