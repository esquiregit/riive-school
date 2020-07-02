<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/student.php";
    require_once "classes/methods.php";
    require_once 'classes/audit_trail.php';
    $_SESSION['riive_school_page'] = 'Students';

    if(!isset($_GET['Rfd5Tf'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'manage-students.php';</script>";
    } else {
        $id = Methods::validate_string($_GET['Rfd5Tf']);
        $conn         = $pdo->open();
        $result 	  = Student::read_student($id, $conn);
        $pdo->open();

        if(!$result) {
            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Student URL Parameters', $conn);
            die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"manage-students.php\";'>Yes Sir!!!</button>");
        }

        $student_image = (empty($result->imagePath) || empty($result->image)) ? 'pictures/avatar.png' : $result->imagePath . '/' . $result->image;
        $school        = $_SESSION['riive_school_name'];
        $firstname     = Methods::strtocapital($result->firstname);
        $othername     = Methods::strtocapital($result->othernames);
        $lastname      = Methods::strtocapital($result->lastname);
        $name          = $firstname . ' ' .$othername . ' ' . $lastname;
        $gender        = Methods::strtocapital($result->gender);
        $date_of_birth = date_format(date_create($result->dob), 'l d F Y');
        $class         = $result->class;
        $student_code  = $result->studentCode;

        $pdo->close();
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Student Details</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
        			<div class="col-md-6">
            			<div class="form-group dark-profile-image">
                            <div class="profile-image" title="<?php echo $name; ?>'s Profile Picture">
                            	<img id="profile-image" src="<?php echo $student_image; ?>" alt="<?php echo $name; ?>'s Profile Picture" class="profile-image" />
                            </div>
                        </div>
            		</div>
            		<div class="col-md-6">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $name; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $class; ?>" />
                        </div>
            			<div class="form-group">
                            <label>Gender</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $gender; ?>" />
                        </div>
            		</div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date Of Birth</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $date_of_birth; ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Student Code</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $student_code; ?>" />
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
                                <h4 class="text-left"><strong>Student Report</strong></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <td colspan="4">
                                <div class="profile-image" title="<?php echo $name; ?>'s Profile Picture" style="width: 250px;height: 250px;margin:20px auto;">
                                    <img id="profile-image" src="<?php echo $student_image; ?>" alt="<?php echo $name; ?>'s Profile Picture" class="profile-image" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Student Name: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">School: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $school; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Gender: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $gender; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Class: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $class; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Date Of Birth: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $date_of_birth; ?></td>
                            <th style="font-weight:700;text-align:right;padding:15px;font-size:17px;">Student Code: </th><td style="font-weight: 400; text-align: left;padding:15px;font-size:17px"><?php echo $student_code; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>