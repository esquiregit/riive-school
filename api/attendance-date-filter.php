<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/attendance.php";
    require_once "classes/methods.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Attendance';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];

    $conn           = $pdo->open();
    @$teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
    if(!$teacher_class && $_SESSION['riive_school_access_level'] == 'Teacher') {
        echo "<script>alert('You Have Not Been Assigned A Class');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $attendance_date = $error = $success = $error_message = $success_message = $attendance_result = '';
        $no_result       = false;
        $attendance_date = Date('Y-m-d');
        $attendance_result = Attendance::read_attendance_by_date_for_teacher($attendance_date, $_SESSION['riive_school_teacher_class'], $conn);

        if(isset($_POST['submit'])) {
            $attendance_date       = Methods::validate_string($_POST['attendance-date']);

            if(empty($attendance_date) || $attendance_date == 'default') {
                $error             = true;
                $error_message     = 'Date of Attendance Required';
            } else {
                $attendance_result = Attendance::read_attendance_by_date_for_teacher($attendance_date, $_SESSION['riive_school_teacher_class'], $conn);
                $no_result         = empty($attendance_result) ? true : false;
            }
        }
    }

    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Attendance By Date</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($attendance_result && $attendance_date) { ?>
                    <button title="Print Table" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                    <?php } ?>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <form action="" method="POST" role="form">
                                <div class="form-group">
                                    <?php if($error){ ?>
                                        <div class="alert alert-danger alert-dismissible fade show text-center">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>ERROR!</strong> <?php echo $error_message; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="row small-form-0">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Date Of Attendance</label>
                                            <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="attendance-date" value="<?php echo $attendance_date; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <button type="submit" name="submit" class="btn btn-rounded btn-info"><i class="fa fa-search"></i> Search Attendance</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php if($attendance_result && $attendance_date) { ?>
                            <div class="card-body">
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Student Name">Student</th>
                                                <th width="20%" title="Click To Sort By Date">Date</th>
                                                <th title="Click To Sort By Status">Status</th>
                                                <th title="Click To Sort By Clock In Time">Clock In Time</th>
                                                <th title="Click To Sort By Clock Out Time">Clock Out Time</th>
                                                <th title="Click To Sort By Pickup Code">Pickup Code</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($attendance_result as $record) { 
                                                $student = $name = Methods::strtocapital(Student::read_student_name($record->student_id, $conn));
                                                if($record->Status == 'Present'){
                                                    $clock_in_time   = $record->clock_in_time;
                                                    $clock_out_time  = $record->clock_out_time == '00:00:00' ? 'Not Yet' : $record->clock_out_time; 
                                                } else {
                                                    $clock_in_time   = '--:--:--'; 
                                                    $clock_out_time  = '--:--:--'; 
                                                }
                                                $pickUpCode      = $record->pickUpCode;
                                                $status          = ($record->Status == 'Present') ? '<span class="badge badge-success">Present</span>' : '<span class="badge badge-danger">Absent</span>';
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'l d F Y'); ?></td>
                                                    <td><?php echo $status; ?></td>
                                                    <td><?php echo $clock_in_time; ?></td>
                                                    <td><?php echo $clock_out_time; ?></td>
                                                    <td><?php echo $pickUpCode; ?></td>
                                                    <td>
                                                        <?php if($record->Status == 'Present'){
                                                            echo "<button class='btn btn-info' title='View Details' onclick='location = \"view-attendance.php?juHytg=$record->id\";'><i class='fa fa-eye'></i></button>";
                                                        } ?>
                                                        <?php if($_SESSION['riive_school_access_level'] == 'Teacher'){
                                                            if($record->Status == 'Present') {
                                                                if($clock_out_time == 'Not Yet'){echo "<button class='btn btn-success' title='Clock Out $name' onclick='var answer = confirm(\"Are You Sure You Want To Clock Out $name?\");if(answer){location = \"clock_out_student.php?juHytg=$record->id&Ui87YHgt=$record->student_id&okiU76=$name&Iui89Okj=$pickUpCode\";}'><i class='fa fa-check'></i></button>";}
                                                            }
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Student</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Pickup Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php } else if($no_result) { ?>
                            <div class="card-body <?php if($no_result) { echo 'show'; } ?>">
                                <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>0 Results!</strong> No Attendance Found For "<?php echo date_format(date_create($attendance_date), 'l d F Y'); ?>"
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid hide">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <?php if($attendance_result) { ?>
                            <div class="card-body" id="printable">
                                <div style="margin-bottom: 50px;">
                                    <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>
                                            <h4 class="text-left">Attendance Report For <strong><?php echo date_format(date_create($attendance_date), 'l d F Y'); ?></strong></h4>
                                        </td>
                                        <td>
                                            <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Pickup Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($attendance_result as $record) { 
                                                $student = $name = Methods::strtocapital(Student::read_student_name($record->student_id, $conn));
                                                if($record->Status == 'Present'){
                                                    $clock_in_time   = $record->clock_in_time;
                                                    $clock_out_time  = $record->clock_out_time == '00:00:00' ? 'Not Yet' : $record->clock_out_time; 
                                                } else {
                                                    $clock_in_time   = '--:--:--'; 
                                                    $clock_out_time  = '--:--:--'; 
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'l d F Y'); ?></td>
                                                    <td><?php echo $record->Status; ?></td>
                                                    <td><?php echo $clock_in_time; ?></td>
                                                    <td><?php echo $clock_out_time; ?></td>
                                                    <td><?php echo $record->pickUpCode; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Student</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Pickup Code</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>