<?php
    require_once "classes/check_login.php";
    require_once "classes/check_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/assessment.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Add Assessment';

    if(!isset($_SESSION['riive_school_assessment_subject']) && !isset($_SESSION['riive_school_assessment_term']) || !isset($_SESSION['riive_school_assessment_academic_year'])) {
        echo "<script>location = 'add_assessment.php';</script>";
    } else {
        $class                    = $_SESSION['riive_school_teacher_class'];
        $subject                  = Methods::strtocapital($_SESSION['riive_school_assessment_subject']);
        $term                     = $_SESSION['riive_school_assessment_term'];
        $academic_year            = $_SESSION['riive_school_assessment_academic_year'];
        $conn                     = $pdo->open();
        $students                 = Student::read_assessment_students_by_class($class, $conn);
        $students_array           = array();
        $class_test_array         = array();
        $assignments_array        = array();
        $attendance_array         = array();
        $interim_assessment_array = array();
        $exams_array              = array();
        $full_class               = ($class == 1 || $class == 2 || $class == 3 || $class == 4 || $class == 5 || $class == 6) ? 'Class ' . $class : $class;
        
        $pdo->close();
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Add Assessment</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <h3 style="font-weight: 600;"><?php echo $subject .' - ' . $full_class . ' - ' . $academic_year; ?></h3>
                            <div class="card-body">
                                <form id="form" action="assessment.php" method="POST">
                                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="add-assessment">
                                        <strong><i class='fa fa-spinner fa-spin'></i> Adding Assessment. Please Wait....</strong>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th width="25%">Student</th>
                                                    <th>Class Test (<?php echo $_SESSION['riive_school_class_tests_max']; ?> Marks)</th>
                                                    <th>Assignments (<?php echo $_SESSION['riive_school_assignments_max']; ?> Marks)</th>
                                                    <th>Attendance (<?php echo $_SESSION['riive_school_attendance_mark_max']; ?> Marks)</th>
                                                    <th>Interim Assessment (<?php echo $_SESSION['riive_school_interim_assessment_max']; ?> Marks)</th>
                                                    <th>Exams (<?php echo $_SESSION['riive_school_exams_score_max']; ?> Marks)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($index = 0; $index < count($students); $index++){
                                                    $nr = $index + 1;
                                                    $studentname = empty($students[$index]->othernames) ? $students[$index]->firstname . ' ' . $students[$index]->lastname : $students[$index]->firstname . ' ' . $students[$index]->othernames . ' ' . $students[$index]->lastname;
                                                ?>
                                                <tr>
                                                    <td><?php echo $nr; ?></td>
                                                    <td>
                                                        <input type="hidden" name="students_array[]" id="student_id" value="<?php echo $students[$index]->studentid; ?>" />
                                                        <?php echo $studentname; ?>        
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_class_tests_max']; ?>" class="form-control" placeholder="...." name="class_test_array[]" id="class_tests" />
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_assignments_max']; ?>" class="form-control" placeholder="...." name="assignments_array[]" id="assignments" />
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_attendance_mark_max']; ?>" class="form-control" placeholder="...." name="attendance_array[]" id="attendance" />
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_interim_assessment_max']; ?>" class="form-control" placeholder="...." name="interim_assessment_array[]" id="interim_assessment" />
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" step="0.5" max="<?php echo $_SESSION['riive_school_exams_score_max']; ?>" class="form-control" placeholder="...." name="exams_array[]" id="exams_score" />
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student</th>
                                                    <th>Class Test</th>
                                                    <th>Assignments</th>
                                                    <th>Attendance</th>
                                                    <th>Interim Assessment</th>
                                                    <th>Exams</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group text-right">
                                                <button type="submit" name="addassessment" id="addassessment" class="btn btn-info m-t-10"><i class="fa fa-file-medical"></i> Submit Assessment</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>