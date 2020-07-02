<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once 'classes/audit_trail.php';
    require_once "classes/methods.php";
    require_once "classes/parent.php";
    $_SESSION['riive_school_page'] = 'Parents';
 
    if(!isset($_GET['kIju87g'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'view-parents.php';</script>";
    } else {
        $id          = Methods::validate_string($_GET['kIju87g']);
    }

    $conn            = $pdo->open();
    $result 	     = Parents::read_parent($id, $conn);
    $pdo->close();

    if(!$result) {
        Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Parent URL Parameters', $conn);
        die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"view-parents.php\";'>Yes Sir!!!</button>");
    }

    $student         = Methods::strtocapital($result->firstname . ' ' . $result->othernames . ' ' . $result->lastname);
    $school          = Methods::strtocapital($result->schoolname);
    $parent_name     = Methods::strtocapital($result->fullname);
    $phone_number    = $result->phone;
    $email           = strtolower($result->email);
    $location        = Methods::strtocapital($result->location);
    $longitude       = $result->longitude;
    $latitude        = $result->latitude;
    $country         = Methods::strtocapital($result->regCountry);
    $payment_model   = Methods::strtocapital($result->payment_model);
    $occupation      = Methods::strtocapital($result->occupation);
    $relation        = Methods::strtocapital($result->relation);
    $last_loc_update = ($result->lastLocUpdate || $result->lastLocUpdate == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($result->lastLocUpdate), 'l d F Y \a\t H:i:s');
    $status          = Methods::strtocapital($result->status);
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Parent Details</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Parent Name</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $parent_name; ?>" />
                        </div>
            		</div>
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Child</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $student; ?>" />
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>School</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $school; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Model</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $payment_model; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Relation</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $relation; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $phone_number; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $email; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Location</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $location; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $country; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Occuation</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $occupation; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Longitude</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $longitude; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Latitude</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $latitude; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Location Update</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $last_loc_update; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $status; ?>" />
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
                                <h4 class="text-left"><strong>Parent Report</strong></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Parent Name: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $parent_name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Student: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $student; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">School: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $school; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Sent By: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $payment_model; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Relation: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $relation; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Phone Number: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $phone_number; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Email Address: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $email; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Location: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $location; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Country: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $country; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Occupation: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $occupation; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Longitude: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $longitude; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Latitude: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $latitude; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Last Location Update: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $last_loc_update; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Status: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $status; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>