<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require 'partials/_nav.php' ?>

    <div class="container my-2">
        <figure class="text-center">
            <blockquote class="blockquote text-danger">
                <p class="h1">Student Registered Data</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                GURU NANAK DEV ENGINEERING COLLEGE
            </figcaption>
        </figure>
    </div>

    <div class="container">
        <div class="center">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Uploaded File</th>
                        <th scope="col">Action</th>
                        <th scope="col">Forward</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "users";

                $conn = mysqli_connect($servername, $username, $password, $database);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Process Approve/Reject before fetching data
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['approve'])) {
                        $id = $_POST['sno'];
                        $approveQuery = "UPDATE `student-registeration` SET status = 'approved' WHERE sno = '$id'";
                        mysqli_query($conn, $approveQuery);

                        // Fetch student URN for file naming
                        $studentQuery = "SELECT urn FROM `student-registeration` WHERE sno = '$id'";
                        $res = mysqli_query($conn, $studentQuery);
                        $student = mysqli_fetch_assoc($res);
                        $urn = $student['urn'];

                        // Generate signed certificate
                        require_once 'generate_signed_certificate.php';
                        $filename = "signed_certificates/{$urn}_certificate.png"; // Or .pdf
                        signCertificate($urn, $filename);

                        // Save path to DB
                        $updateFilePath = "UPDATE `student-registeration` SET file_path = '$filename' WHERE sno = '$id'";
                        mysqli_query($conn, $updateFilePath);

                        echo '<script>alert("User Approved!"); window.location.href="Teacher_Approval.php";</script>';
                    }
                    
                    if (isset($_POST['deny'])) {
                        $id = $_POST['sno'];
                        $comment = mysqli_real_escape_string($conn, $_POST['rejection_comment']);
                        $denyQuery = "UPDATE `student-registeration` SET status = 'rejected', rejection_comment = '$comment' WHERE sno = '$id'";
                        mysqli_query($conn, $denyQuery);
                        echo '<script>alert("User Rejected!"); window.location.href="Teacher_Approval.php";</script>';
                    }
                    
                    if (isset($_POST['forward'])) {
                        $id = $_POST['sno'];
                        $forward_to = $_POST['forward_to'];
                        $role = $_SESSION['role'];
                    
                        // Check current stage before forwarding (security)
                        $checkStageQuery = "SELECT current_stage FROM `student-registeration` WHERE sno = '$id'";
                        $checkResult = mysqli_query($conn, $checkStageQuery);
                        $stageRow = mysqli_fetch_assoc($checkResult);
                    
                        if ($stageRow['current_stage'] === $role) {
                            $forward_query = "UPDATE `student-registeration` SET current_stage = '$forward_to' WHERE sno = '$id'";
                            $result = mysqli_query($conn, $forward_query);
                    
                            if ($result) {
                                echo '<script>alert("File forwarded to ' . $forward_to . ' successfully!"); window.location.href="Teacher_Approval.php";</script>';
                            } else {
                                echo '<script>alert("Failed to forward the file.");</script>';
                            }
                        } else {
                            echo '<script>alert("You are not authorized to forward this file.");</script>';
                        }
                    }
                    
                }

                // Fetch pending students
                $query = "SELECT * FROM `student-registeration` WHERE status = 'pending' ORDER BY sno ASC";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_array($result)) {
                ?>
                    <?php
                    $role = $_SESSION['role'];  // 'Teacher', 'HOD', or 'Principal'
                    $current_stage = $row['current_stage'];
                    ?>

                    <tr>
                        <th scope="row"><?php echo $row['sno']; ?></th>
                        <td><?php echo htmlspecialchars($row['fname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])): ?>
                                <a href="../<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                    View File
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No file uploaded</span>
                            <?php endif; ?>
                        </td>
                        <td>

                            <form action="Teacher_Approval.php" method="POST">
                                <input type="hidden" name="sno" value="<?php echo $row['sno']; ?>"/>

                                <?php if ($role == $current_stage): ?>
                                    <textarea name="rejection_comment" placeholder="Add comment for rejection (optional)"></textarea>
                                    <button type="submit" class="btn btn-success btn-sm" name="approve">Approve</button>
                                    <button type="submit" class="btn btn-danger btn-sm" name="deny">Reject</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Approval Locked</button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td>
                            <?php if ($role == $current_stage): ?>
                                <form action="Teacher_Approval.php" method="POST">
                                    <input type="hidden" name="sno" value="<?php echo $row['sno']; ?>"/>
                                    <select name="forward_to" class="form-select form-select-sm d-inline w-auto" required>
                                        <option value="" disabled selected>Choose</option>
                                        <?php if ($role == 'Teacher'): ?>
                                            <option value="HOD">HOD</option>
                                            <option value="Principal">Principal</option>
                                        <?php elseif ($role == 'HOD'): ?>
                                            <option value="Principal">Principal</option>
                                        <?php endif; ?>
                                    </select>
                                    <button type="submit" name="forward" class="btn btn-warning btn-sm mt-1">Forward</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">At: <?php echo $current_stage; ?></span>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php
                } // End of while
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
