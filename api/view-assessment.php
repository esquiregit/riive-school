<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once 'classes/audit_trail.php';
    require_once "classes/assessment.php";
    require_once "classes/methods.php";
    require_once "classes/school.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'View Assessment';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER']; 

    if(!isset($_GET['uy76Tygf4'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $id = Methods::validate_string($_GET['uy76Tygf4']);
    }

    $conn   = $pdo->open();
    $result = Assessment::read_assessment($id, $conn);

    if(!$result) {
        Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Assessment URL Parameters', $conn);
        die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"$previous_page\";'>Yes Sir!!!</button>");
    }

    $student_id         = $result->student_id;
    $student_name       = Methods::strtocapital(Student::read_student_name($student_id, $conn));
    $school_name        = Methods::strtocapital(School::read_school_name_by_id($result->School_id, $conn));
    $class              = $result->class;
    $term               = $result->term;
    $academic_year      = $result->academic_year;
    $subject            = $result->subject;
    $class_tests        = $result->class_tests;
    $assignments        = $result->assignments;
    $interim_assessment = $result->interim_assessment;
    $attendance_mark    = $result->attendance_mark;
    $exams_score        = $result->exams_score;
    $total_score        = $result->total_score;
    $grade              = $result->grade;
    $remarks            = $result->remarks;
    $date_recorded      = date_format(date_create($result->date_entered), 'l d F Y \a\t H:i:s');
    
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Assessment</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="container-fluid">
        		<div class="row display-form">
            		<div class="col-md-4">
                        <div class="form-group">
                            <label>Student</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $student_name; ?>" />
                        </div>
            		</div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Class</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $class; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Term</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $term; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Academic Year</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $academic_year; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Subject</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $subject; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Class Tests</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $class_tests; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Assignments</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $assignments; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Interim Assessment</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $interim_assessment; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Attendance</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $attendance_mark; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Exams</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $exams_score; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Total Score</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $total_score; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Grade</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $grade; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $remarks; ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date Recorded</label>
                            <input readonly="readonly" type="text" class="form-control" value="<?php echo $date_recorded; ?>" />
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
                                <h4 class="text-left">Assessment Report For <?php echo '<strong>' . $student_name . '</strong>'; ?></h4>
                            </td>
                            <td>
                                <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                            </td>
                        </tr>
                    </table>
                    <table style="border-top:3px solid #e5e5e5;width:100%;">
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Student: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $student_name; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">School: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $school_name; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Class: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $class; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Term: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $term; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Academic Year: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $academic_year; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Subject: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $subject; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Class Tests: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $class_tests; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Assignments: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $assignments; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Attendance Score: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $attendance_mark; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Interim Assessment: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $interim_assessment; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Exams Score: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $exams_score; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Total Score: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $total_score; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Grade: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $grade; ?></td>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Remarks: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $remarks; ?></td>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-align:right;padding:20px;font-size:17px;">Date Recorded: </th><td style="font-weight: 400; text-align: left;padding:20px;font-size:17px"><?php echo $date_recorded; ?></td>
                    </table>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>