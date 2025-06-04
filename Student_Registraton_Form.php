<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$urn_session = $_SESSION['urn'];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STUDENT REGISTRATION</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-registration .select-input.form-control[readonly]:not([disabled]) {
            font-size: 1rem;
            line-height: 2.15;
            padding-left: .75em;
            padding-right: .75em;
        }
        .card-registration .select-arrow {
            top: 13px;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $fathername = $_POST['fathername'];
    $Mothername = $_POST['Mothername'];
    $CRN = $_POST['CRN'];
    $URN = $_POST['URN'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $email = $_POST['email'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["uploadFile"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = array("pdf", "jpg", "jpeg", "png");

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_file)) {
            $uploadedPath = $target_file;
        } else {
            echo "<div class='alert alert-danger'>Error uploading the file.</div>";
            $uploadedPath = "";
        }
    } else {
        echo "<div class='alert alert-danger'>Only PDF, JPG, and PNG files are allowed.</div>";
        $uploadedPath = "";
    }

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "users");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO `student-registeration` 
            (`fname`, `lname`, `fathername`, `Mothername`, `CRN`, `URN`, `course`, `department`, `email`, `file_path`, `dt`) 
            VALUES 
            ('$fname', '$lname', '$fathername', '$Mothername', '$CRN', '$URN', '$course', '$department', '$email', '$uploadedPath', current_timestamp())";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success! </strong> Dear ' . $fname . ' ' . $lname . ', Your Entry Has Been Submitted. Download Your Certificate <a href="/NDA/Check_Certification.php">here</a>.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Technical issue. Entry not submitted.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
    }
}
?>

<!--<figure class="text-center mt-4">
     <blockquote class="blockquote"> 
        <p class="h1">Application Form</p>
    </blockquote>
    <figcaption class="blockquote-footer">
        GURU NANAK DEV ENGINEERING COLLEGE
    </figcaption> 
</figure> -->

<section class="h-100 bg-dark">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card card-registration my-4 justify-content-center align-items-center">
                    <div class="col-xl-6">
                        <div class="card-body p-md-5 text-black">
                            <h3 class="mb-5 text-uppercase text-center">Application form</h3>
                            <form action="/NDA/Student_Registraton_Form.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" name="fname" class="form-control" id="fname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" name="lname" class="form-control" id="lname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fathername" class="form-label">Father Name</label>
                                    <input type="text" name="fathername" class="form-control" id="fathername" required>
                                </div>
                                <div class="mb-3">
                                    <label for="Mothername" class="form-label">Mother Name</label>
                                    <input type="text" name="Mothername" class="form-control" id="Mothername" required>
                                </div>
                                <div class="mb-3">
                                    <label for="CRN" class="form-label">College Roll Number</label>
                                    <input type="text" name="CRN" class="form-control" id="CRN" required>
                                </div>
                                <div class="mb-3">
                                    <label for="URN" class="form-label">University Roll Number</label>
                                    <input type="text" name="URN" class="form-control" id="URN" value="<?php echo $urn_session; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="course" class="form-label">Course</label>
                                    <select class="form-control" name="course" id="course">
                                        <option>B.TECH</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-control" name="department" id="department">
                                        <option>COMPUTER SCIENCE AND ENGINEERING</option>
                                        <option>CIVIL ENGINEERING</option>
                                        <option>ELECTRONICS AND COMMUNICATION ENGINEERING</option>
                                        <option>INFORMATION TECHNOLOGY</option>
                                        <option>MECHANICAL ENGINEERING</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" required>
                                    <div class="form-text">We'll never share your email with anyone else.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="uploadFile" class="form-label">Upload File (PDF/Image)</label>
                                    <input type="file" name="uploadFile" class="form-control" id="uploadFile" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
