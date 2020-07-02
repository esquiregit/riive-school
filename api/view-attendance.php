<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once 'classes/audit_trail.php';
    require_once "classes/attendance.php";
    require_once "classes/methods.php";
    $_SESSION['riive_school_page'] = 'Attendance';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER']; 

    if(!isset($_GET['juHytg'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $id = Methods::validate_string($_GET['juHytg']);
    }

    $conn   = $pdo->open();
    $result = Attendance::read_attendance_by_id($id, $conn);
    $pdo->close();

    if(!$result) {
        Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Attendance URL Parameters', $conn);
        die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"$previous_page\";'>Yes Sir!!!</button>");
    }

    $student_id     = Methods::strtocapital($result->firstname . ' ' . $result->othernames . ' ' . $result->lastname);
    $clock_in_time  = date_format(date_create($result->clock_in_time), 'H:i:s');
    $clock_out_time = ($result->clock_out_time !== '00:00:00') ? date_format(date_create($result->clock_out_time), 'H:i:s') : 'Not Yet';
    $date           = date_format(date_create($result->date), 'l d F Y');
    $pick_up_code   = $result->pickUpCode;
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Attendance Details</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Student</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $student_id; ?>" />
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $date; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pickup Code</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $pick_up_code; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Clock In Time</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $clock_in_time; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Clock Out Time</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $clock_out_time; ?>" />
                        </div>
                    </div>
        		</div>
            </div>

            <div class="container-fluid hide">
                <div class="row display-form" id="printable">
                    <div style="margin-bottom: 50px;">
                        <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                    </div>
                    <table class="table">
                        <tr>
                            <td>
                                <h4 class="text-left">Attendance Report For <?php echo '<strong>' . $student_id . '</strong>'; ?></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Student: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $student_id; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Pickup Code: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $pick_up_code; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Date: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $date; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Clock Out Time: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $clock_out_time; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Clock In Time: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $clock_in_time; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>