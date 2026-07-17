<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('inc/config.php');

if (!isset($_SESSION['userid'])) {
    echo "<p>You must be logged in to view your bookings.</p>";
    exit();
}

$userid = $_SESSION['userid'];

// =====================
// CANCEL REQUEST LOGIC
// =====================
if(isset($_GET['cancelreq'])){
    $bid = intval($_GET['cancelreq']);

    // check duplicate request
    $check = $dbh->prepare("SELECT * FROM tblcancelrequests WHERE BookingId=:bid AND Status=0");
    $check->bindParam(':bid',$bid);
    $check->execute();

    if($check->rowCount()==0){
        $sql = "INSERT INTO tblcancelrequests (BookingId, UserId) 
                VALUES (:bid, :uid)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bid',$bid);
        $query->bindParam(':uid',$userid);
        $query->execute();

        echo "<script>alert('Cancel request sent to admin');</script>";
    } else {
        echo "<script>alert('Request already sent');</script>";
    }

    echo "<script>window.location='my-booking.php';</script>";
}

// =====================
// FETCH BOOKINGS
// =====================
$stmt = $dbh->prepare("
    SELECT tblbooking.*, tbltourpackages.PackageName 
    FROM tblbooking 
    JOIN tbltourpackages 
    ON tblbooking.PackageId = tbltourpackages.PackageId
    WHERE tblbooking.UserId = :userid 
    ORDER BY tblbooking.RegDate DESC
");
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'inc/head.php'; ?>

<div class="wrapper">

<?php include 'inc/header.php'; ?>

<!-- PAGE HEADER (same as your design) -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-caption text-center">
                    <h1 class="page-title">My Bookings</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="table-responsive">
                    <table class="table table-striped">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Package Name</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Comments</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if(count($bookings)>0): ?>
                            <?php foreach ($bookings as $index => $booking): ?>
                                <tr>
                                    <td><?php echo $index+1; ?></td>
                                    <td><?php echo $booking['PackageId']; ?></td>
                                    <td><?php echo $booking['PackageName']; ?></td>
                                    <td><?php echo $booking['FromDate']; ?></td>
                                    <td><?php echo $booking['ToDate']; ?></td>
                                    <td><?php echo $booking['Comment']; ?></td>

                                    <td>
                                        <?php
                                        if($booking['status']==0){
                                            echo "Pending";
                                        }
                                        elseif($booking['status']==1){
                                            echo "Confirmed<br>";
                                            ?>
                                            <a href="?cancelreq=<?php echo $booking['BookingId']; ?>"
                                               onclick="return confirm('Send cancel request?')"
                                               class="btn btn-danger btn-sm mt-1">
                                               Cancel Request
                                            </a>
                                            <?php
                                        }
                                        elseif($booking['status']==2){
                                            echo "<span style='color:red;'>Cancelled</span>";
                                        }
                                        ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No bookings found.</td>
                            </tr>
                        <?php endif; ?>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

</div>