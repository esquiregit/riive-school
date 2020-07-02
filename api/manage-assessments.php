<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/conn.php";
    require_once "classes/assessment.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Manage Assessments';

    $conn        = $pdo->open();
    if($_SESSION['riive_school_access_level'] == "School Admin")
        $assessments = Assessment::read_assessments_for_admin($conn);
    else if($_SESSION['riive_school_access_level'] == "Teacher")
        $assessments = Assessment::read_assessments_for_teacher($conn, $_SESSION['riive_school_user_id']);
    $pdo->close();
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
                                <?php if($assessments) { ?>
                                <h4 class="card-title">
                                    <button title="Print Table" class="btn btn-info pull-right" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                                </h4>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Student">Student</th>
                                                <th title="Click To Sort By Class">Class</th>
                                                <th title="Click To Sort By Term">Term</th>
                                                <th title="Click To Sort By Academic Year">Academic Year</th>
                                                <th title="Click To Sort By Subject">Subject</th>
                                                <th title="Click To Sort By Total Score">Total Score</th>
                                                <th title="Click To Sort By Grade">Grade</th>
                                                <th width="18%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php
                                                foreach ($assessments as $assessment) {
                                                    $a_id    = $assessment->a_id;
                                                    $student = Student::read_student_name($assessment->student_id, $conn);
                                                    $subject = Methods::strtocapital($assessment->subject);
                                            ?>
                                            <tr>
                                                <td><?php echo $student; ?></td>
                                                <td><?php echo $assessment->class; ?></td>
                                                <td><?php echo $assessment->term; ?></td>
                                                <td><?php echo $assessment->academic_year; ?></td>
                                                <td><?php echo $subject; ?></td>
                                                <td><?php echo $assessment->total_score; ?></td>
                                                <td><?php echo $assessment->grade; ?></td>
                                                <td>
                                                    <div>
                                                    <?php echo "<button class='btn btn-success' title='View Details' onclick='location = \"view-assessment.php?uy76Tygf4=$a_id\";'><i class='fa fa-eye'></i></button>"; ?>
                                                    <?php if($_SESSION['riive_school_access_level'] == 'Teacher') {
                                                        echo "<button class='btn btn-info' title='Edit Assessment' onclick='location = \"edit-assessment.php?uy76Tygf4=$a_id\";'><i class='fa fa-edit'></i></button>";
                                                    } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Student</th>
                                                <th>Class</th>
                                                <th>Term</th>
                                                <th>Academic Year</th>
                                                <th>Subject</th>
                                                <th>Total Score</th>
                                                <th>Grade</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No Assessments Found
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid hide">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <div class="card-body" id="printable">
                                <div style="margin-bottom: 50px;">
                                    <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>
                                            <h4 class="text-left"><strong>Assessments Report</strong></h4>
                                        </td>
                                        <td>
                                            <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                                <?php if($assessments) { ?>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Class</th>
                                                <th>Term</th>
                                                <th>Academic Year</th>
                                                <th>Subject</th>
                                                <th>Total Score</th>
                                                <th>Grade</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <?php
                                                foreach ($assessments as $assessment) {
                                                    $student = Student::read_student_name($assessment->student_id, $conn);
                                                    $subject = Methods::strtocapital($assessment->subject);
                                            ?>
                                            <tr>
                                                <td><?php echo $student; ?></td>
                                                <td><?php echo $assessment->class; ?></td>
                                                <td><?php echo $assessment->term; ?></td>
                                                <td><?php echo $assessment->academic_year; ?></td>
                                                <td><?php echo $subject; ?></td>
                                                <td><?php echo $assessment->total_score; ?></td>
                                                <td><?php echo $assessment->grade; ?></td>
                                                <td><?php echo $assessment->remarks; ?></td>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Student</th>
                                                <th>Class</th>
                                                <th>Term</th>
                                                <th>Academic Year</th>
                                                <th>Subject</th>
                                                <th>Total Score</th>
                                                <th>Grade</th>
                                                <th>Remark</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>