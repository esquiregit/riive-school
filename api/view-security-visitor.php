<?php
    require_once "classes/visitor.php";
    require_once "classes/check_login.php";
    require_once "classes/methods.php";
    require_once 'classes/audit_trail.php';
    $_SESSION['riive_school_page'] = 'Security';

    if(!isset($_GET['mWas23'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'view-security.php';</script>";
    } else {
        $id = Methods::validate_string($_GET['mWas23']);
    }

    $conn   = $pdo->open();
    $result = Visitor::read_visitor_by_id($id, $conn);
    $pdo->close();

    if(!$result) {
        Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Security Visitor URL Parameters', $conn);
        die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"view-security.php\";'>Yes Sir!!!</button>");
    }

    $visitor_image    = (empty($result->imagePath) || empty($result->image)) ? 'pictures/avatar.png' : $result->imagePath . '/' . $result->image;
    $visitor_name     = Methods::strtocapital($result->visitorName);
    $person_to_visit  = Methods::strtocapital($result->personToVisit);
    $purpose_of_visit = Methods::strtocapital($result->purposeOfVisit);
    $security_person  = Methods::strtocapital($result->name);
    $visitor_phone    = $result->visitorNumber;
    $clock_in_time    = date_format(date_create($result->clockInTime), 'l d F Y \a\t H:i:s');
    $clock_out_time   = ($result->clockOutTime !== '0000-00-00 00:00:00') ? date_format(date_create($result->clockOutTime), 'l d F Y \a\t H:i:s') : 'Not Yet';
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white"><?php echo Methods::strtocapital($visitor_name); ?>'s Visit Details</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Visitor Info" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
        			<div class="col-md-6">
            			<div class="form-group dark-profile-image">
                            <div class="profile-image" title="<?php echo $visitor_name; ?>'s Picture">
                            	<img id="profile-image" src="<?php echo $visitor_image; ?>" alt="<?php echo $visitor_name; ?>'s Picture" class="profile-image" />
                            </div>
                        </div>
            		</div>
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Visitor Name</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $visitor_name; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Person To Visit</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $person_to_visit; ?>" />
                        </div>
            			<div class="form-group">
                            <label>Purpose Of Visit</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $purpose_of_visit; ?>" />
                        </div>
            		</div>
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Security Personnel</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $security_person; ?>" />
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Visitor Phone Number</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $visitor_phone; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Clock In Time</label>
                            <input readonly="readonly"  type="text" class="form-control" value="<?php echo $clock_in_time; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                <h4 class="text-left">Visitation Report For <?php echo '<strong>' . $visitor_name . '</strong>'; ?></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <td colspan="4">
                                <div class="profile-image" title="<?php echo $visitor_name; ?>'s Profile Picture" style="width: 250px;height: 250px;margin:20px auto;">
                                    <img id="profile-image" src="<?php echo $admin_image; ?>" alt="<?php echo $visitor_name; ?>'s Profile Picture" class="profile-image" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Visitor Name: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $visitor_name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Person To Visit: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $person_to_visit; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Purpose Of Visit: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $purpose_of_visit; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Security Personnel: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $security_person; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Clock In Time: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $clock_in_time; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Clock Out Time: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $clock_out_time; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Visitor Phone Number: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $visitor_phone; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>