<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once 'classes/audit_trail.php';
    require_once "classes/assessment.php";
    require_once "classes/methods.php";
    require_once "classes/school.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Edit Assessment';
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
                    <h3 class="text-white">Edit Assessment</h3>
                </div>
                <div class="col-md-7 text-right"></div>
            </div>

            <div class="container-fluid">
                <form id="form" action="assessment.php" method="POST">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="edit-assessment">
                        <strong><i class='fa fa-spinner fa-spin'></i> Saving Assessment. Please Wait....</strong>
                    </div>
        		    <div class="row inside-form">  
                		<div class="col-md-4">
                            <div class="form-group">
                                <label>Student</label>
                                <input readonly="readonly" type="text" class="form-control" name="student_name" id="student_name" value="<?php echo $student_name; ?>" />
                                <input type="hidden" name="a_id" id="a_id" value="<?php echo $id; ?>" />
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
                                <input readonly="readonly" class="form-control" type="text" value="<?php echo $term; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Academic Year</label>
                                <input readonly="readonly" class="form-control" type="text" value="<?php echo $academic_year; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subject</label>
                                <input readonly="readonly" class="form-control" type="text" name="subject" id="subject" value="<?php echo $subject; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Class Tests</label>
                                <input class="form-control" type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_class_tests_max']; ?>" name="class_tests" id="class_tests" value="<?php echo $class_tests; ?>" />
                                <input type="hidden" name="class_tests_hidden" id="class_tests_hidden" value="<?php echo $class_tests; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Assignments</label>
                                <input class="form-control" type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_assignments_max']; ?>" name="assignments" id="assignments" value="<?php echo $assignments; ?>" />
                                <input type="hidden" name="assignments_hidden" id="assignments_hidden" value="<?php echo $assignments; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Interim Assessment</label>
                                <input class="form-control" type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_interim_assessment_max']; ?>" name="interim_assessment" id="interim_assessment" value="<?php echo $interim_assessment; ?>" />
                                <input type="hidden" name="interim_assessment_hidden" id="interim_assessment_hidden" value="<?php echo $interim_assessment; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Attendance</label>
                                <input class="form-control" type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_attendance_mark_max']; ?>" name="attendance_mark" id="attendance_mark" value="<?php echo $attendance_mark; ?>" />
                                <input type="hidden" name="attendance_mark_hidden" id="attendance_mark_hidden" value="<?php echo $attendance_mark; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Exams</label>
                                <input class="form-control" type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_exams_score_max']; ?>" name="exams_score" id="exams_score" value="<?php echo $exams_score; ?>" />
                                <input type="hidden" name="exams_score_hidden" id="exams_score_hidden" value="<?php echo $exams_score; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Score</label>
                                <input readonly="readonly" class="form-control" type="text" name="total_score" id="total_score" value="<?php echo $total_score; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Grade</label>
                                <input readonly="readonly" type="text" name="grade" id="grade" class="form-control" value="<?php echo $grade; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input readonly="readonly" type="text" name="remarks" id="remarks" class="form-control" value="<?php echo $remarks; ?>" />
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" name="editassessment" id="editassessment" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Save Assessment</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>