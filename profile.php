<?php
session_start();

// Show errors (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('inc/config.php');

// Redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['userid'];
$successMsg = $errorMsg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['FullName']);
    $email = trim($_POST['EmailId']);
    $phone = trim($_POST['MobileNumber']);

    try {
        $stmt = $dbh->prepare("UPDATE tblusers 
                               SET FullName = :name, EmailId = :email, MobileNumber = :phone 
                               WHERE id = :id");

        $stmt->execute([
            ':name'  => $fullName,
            ':email' => $email,
            ':phone' => $phone,
            ':id'    => $userId
        ]);

        $successMsg = "Profile updated successfully!";
    } catch (PDOException $e) {
        $errorMsg = "Error: " . $e->getMessage();
    }
}

// Fetch user data
$stmt = $dbh->prepare("SELECT FullName, EmailId, MobileNumber FROM tblusers WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}
?>

<?php include 'inc/head.php'; ?>

<div class="wrapper">

<?php include 'inc/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-caption">
                    <h1 class="page-title">Profile</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profile Section -->
<div class="content">
    <div class="container">
        <div class="row">	
            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">

                <!-- Success Message -->
                <?php if ($successMsg): ?>
                    <div class="alert alert-success"><?php echo $successMsg; ?></div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if ($errorMsg): ?>
                    <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
                <?php endif; ?>

                <!-- Profile Form -->
                <form method="POST">

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" name="FullName"
                               value="<?php echo htmlspecialchars($user['FullName']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control" name="MobileNumber"
                               value="<?php echo htmlspecialchars($user['MobileNumber']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="EmailId"
                               value="<?php echo htmlspecialchars($user['EmailId']); ?>" required>
                    </div>

                    

                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>