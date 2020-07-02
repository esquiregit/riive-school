<?php
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];

    if(Date('l') != 'Saturday' && Date('l') != 'Sunday') {
        require_once "classes/check_login.php";
        require_once "classes/check_admin_teacher.php";
        require_once "classes/after_nine_marking_xml.php";
        require_once "classes/conn.php";
        require_once "classes/teacher.php";

        $conn          = $pdo->open();
        $teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
        $pdo->close();

        if(!$teacher_class) {
            echo "<script>alert('You Have Not Been Assigned A Class');</script>";
            echo "<script>location = '$previous_page';</script>";
        } else {
            require_once "classes/student.php";
            $_SESSION['riive_school_page'] = 'Attendance';

            $conn           = $pdo->open();
            $students       = Student::read_attendance_students_by_class($teacher_class, $conn);
            $students_array = array();
            $nr             = 0;
            
            $pdo->close();
        }
    } else {
        echo "<script>alert('Can\'t Mark Attendance On Weekend');</script>";
        echo "<script>location = '$previous_page';</script>";
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white"><?php echo $_SESSION['riive_school_page']; ?></h3>
                </div>

            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-red mt-0">
                            <div class="card-body">
                                <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="mark-attendance">
                                    <strong>Marking Attendance. Please Wait....</strong>
                                </div>
                                <?php if($students) { ?>
                                <h4 class="card-title"></h4>
                                <form id="form" class="form" action="attendance.php" method="POST">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="10%">#</th>
                                                    <th width="70%">Student</th>
                                                    <th width="20%">Attendance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($students as $student){
                                                    $nr++;
                                                    $studentname = empty($student->othernames) ? $student->lastname . ' ' . $student->firstname : $student->lastname . ' ' . $student->firstname . ' ' . $student->othernames;
                                                ?>
                                                <tr>
                                                    <td><?php echo $nr; ?></td>
                                                    <td>
                                                        <input type="hidden" name="teacher_class" id="teacher_class" value="<?php echo $teacher_class; ?>" />
                                                        <input type="hidden" name="students_array[]" id="students_array" value="<?php echo $student->studentid; ?>" />
                                                        <?php echo $studentname; ?>        
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" name="students_array[]" id="attendance_array" value="Absent">
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student</th>
                                                    <th>Attendance</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group text-right">
                                                <button type="submit" name="markattendance" id="markattendance" class="btn btn-info m-t-10"><i class="fa fa-save"></i> Submit Attendance</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20 m-t-10">
                                        <strong>0 Students Left!</strong> Attendance Marked For Today
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>