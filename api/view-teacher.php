<?php
    require_once "classes/teacher.php";
    require_once "classes/check_login.php";
    require_once "classes/methods.php";
    require_once 'classes/audit_trail.php';
    $_SESSION['riive_school_page'] = 'Teachers';
 
    if(!isset($_GET['iKju7y'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'view-teachers.php';</script>";
    } else {
        $id     = Methods::validate_string($_GET['iKju7y']);
        $conn   = $pdo->open();
        $result = Teacher::read_teacher($id, $conn);
        $pdo->close();

        if(!$result) {
            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Teacher URL Parameters', $conn);
            die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"view-teachers.php\";'><i class='fa fa-eye'></i> Yes Sir!!!</button>");
        }

        $teacher_name = Methods::strtocapital($result->name);
        $phone_number = $result->contact;
        $email        = $result->email;
        $country      = Methods::strtocapital(Teacher::read_country_name_by_id($result->country_id, $conn));
        $accountType  = Methods::strtocapital($result->accountType);
        $status       = Methods::strtocapital($result->status);

        $pdo->close();
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Teacher Details</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
                    <div class="slim">
                		<div class="col-md-12">
                            <div class="form-group">
                                <label>Teacher Name</label>
                                <input readonly="readonly"  type="text" class="form-control" value="<?php echo $teacher_name; ?>" />
                            </div>
                		</div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input readonly="readonly"  type="text" class="form-control" value="<?php echo $email; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input readonly="readonly"  type="text" class="form-control" value="<?php echo $phone_number; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Account Type</label>
                                <input readonly="readonly"  type="text" class="form-control" value="<?php echo $accountType; ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <input readonly="readonly"  type="text" class="form-control" value="<?php echo $status; ?>" />
                            </div>
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
                                <h4 class="text-left">Teacher Report For <strong><?php echo $teacher_name; ?></strong></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Teacher Name: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $teacher_name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Email Address: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Contact Number: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $phone_number; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Account Type: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $accountType; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Status: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $status; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>