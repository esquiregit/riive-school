<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/pickup.php";
    require_once "classes/methods.php";
    require_once 'classes/audit_trail.php';
    $_SESSION['riive_school_page'] = 'Pickups';

    @$previous_page = $_SERVER['HTTP_REFERER'];
    if(!isset($_GET['AvgFvc'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $id = Methods::validate_string($_GET['AvgFvc']);
    }

    $conn         = $pdo->open();
    $result       = Pickup::read_pickup($id, $conn);
    $pdo->close();

    if(!$result) {
        Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Pickup URL Parameters', $conn);
        die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"view-pickups.php\";'>Yes Sir!!!</button>");
    }

    $pickup_image = (empty($result->imagePath) || empty($result->image)) ? 'pictures/avatar.png' : $result->imagePath . '/' . $result->image;
    $pickup_name  = Methods::strtocapital($result->pickUpPerson);
    $pickup_type  = Methods::strtocapital($result->pickUpType);
    $pickup_phone = $result->phone;
    $pickup_code  = $result->code;
    $student_id   = Methods::strtocapital($result->firstname . ' ' . $result->othernames . ' ' . $result->lastname);
    $date         = date_format(date_create($result->date), 'l d F Y \a\t H:i:s');
    $sent_by      = Methods::strtocapital($result->fullname);
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Pickup By <?php echo Methods::strtocapital($pickup_name); ?></h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
        			<div class="col-md-6">
            			<div class="form-group dark-profile-image">
                            <div class="profile-image" title="<?php echo $pickup_name; ?>'s Picture">
                            	<img id="profile-image" src="<?php echo $pickup_image; ?>" alt="<?php echo $pickup_name; ?>'s Picture" class="profile-image" />
                            </div>
                        </div>
            		</div>
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Pickup Name</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $pickup_name; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Student Name</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $student_id; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Sent By</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $sent_by; ?>" />
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pickup Type</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $pickup_type; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pickup Code</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $pickup_code; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $date; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pickup Person Number</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $pickup_phone; ?>" />
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
                                <h4 class="text-left"><strong>Pickup Report</strong></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Pickup Person: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $pickup_name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Student: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $student_id; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Sent By: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $sent_by; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">School: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $school_name; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Country: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $country; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Pickup Type: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $pickup_type; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Pickup Code: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $pickup_code; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Date: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $date; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Pickup Person Number: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $pickup_phone; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>