<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/student.php";
    require_once "classes/methods.php";
    $_SESSION['riive_school_page'] = 'Students';

    $conn                   = $pdo->open();
    $student_class          = $error = $success = $error_message = $success_message = $student_result = $school = $class_name ='';
    $no_result              = false;

    if(isset($_POST['submit'])) {
        $student_class        = Methods::validate_string($_POST['student-class']);

        if(empty($student_class) || $student_class == 'default') {
            $error          = true;
            $error_message  = 'Please Select A Class';
        } else {
            $student_result = Student::read_students_by_class($student_class, $conn);
            $no_result      = empty($student_result) ? true : false;

            if(strrpos($student_class, 'JHS') === false && strrpos($student_class, 'SHS') === false && strrpos($student_class, 'KG') === false && strrpos($student_class, 'Nursery') === false && strrpos($student_class, 'Creche') === false) {
                $class_name = 'Class ' . $student_class;
            } else {
                $class_name = $student_class;
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
                    <h3 class="text-white">View Students By Class</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($student_result) { ?>
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                    <?php } ?>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-red mt-0">
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
                                            <label>Class</label>
                                            <select name="student-class" class="form-control">
                                                <option value="default" <?php if(isset($class) && $class == 'default'){echo 'selected';}?>>-- Select --</option>
                                                <?php foreach(Student::get_classes() as $classs){  ?>
                                                <option value="<?php echo $classs; ?>" <?php if(isset($class) && $class == $classs){echo 'selected';}?>><?php echo $classs; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <button type="submit" name="submit" class="btn btn-rounded btn-info"><i class="fa fa-search"></i> Search Students</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php if($student_result) { ?>
                            <div class="card-body">
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name">Name</th>
                                                <th title="Click To Sort By Gender">Gender</th>
                                                <th title="Click To Sort By Class">Class</th>
                                                <th title="Click To Sort By Student Code">Student Code</th>
                                                <th width="10%">Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($student_result as $record) {
                                                $name   = Methods::strtocapital($record->lastname) . ' ' . Methods::strtocapital($record->firstname) . ' ' . Methods::strtocapital($record->othernames);
                                                $school = $record->schoolname;
                                                $image  = (empty($record->imagePath) || empty($record->image)) ? 'pictures/avatar.png' : $record->imagePath . '/' . $record->image;
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->gender; ?></td>
                                                    <td><?php echo $record->class; ?></td>
                                                    <td><?php echo $record->studentCode; ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-success' title='View $name' onclick='location = \"view-student.php?Rfd5Tf=$record->studentid\";'><i class='fa fa-eye'></i></button>"; ?>
                                                        <?php echo "<button class='btn btn-info' title='Edit $name' onclick='location = \"edit-student.php?Rfd5Tf=$record->studentid\";'><i class='fa fa-edit'></i></button>"; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Class</th>
                                                <th>Student Code</th>
                                                <th>Image</th>
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
                                    <strong>0 Results!</strong> No Students Found In <?php echo Methods::strtocapital($student_class); ?>
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
                            <div class="card-body" id="printable">
                                <div style="margin-bottom: 50px;">
                                    <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>
                                            <h4 class="text-left"><strong>Students Report</strong></h4>
                                        </td>
                                        <td>
                                            <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                                <?php if($student_result) { ?>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name">Name</th>
                                                <th title="Click To Sort By Gender">Gender</th>
                                                <th title="Click To Sort By Class">Class</th>
                                                <th title="Click To Sort By Date Of Birth">Date Of Birth</th>
                                                <th title="Click To Sort By Student Code">Student Code</th>
                                                <th width="10%">Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($student_result as $record) {
                                                $name   = Methods::strtocapital($record->lastname) . ' ' . Methods::strtocapital($record->firstname) . ' ' . Methods::strtocapital($record->othernames);
                                                $image  = (empty($record->imagePath) || empty($record->image)) ? 'pictures/avatar.png' : $record->imagePath . '/' . $record->image;
                                                $date_of_birth = date_format(date_create($record->dob), 'd F Y');
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->gender; ?></td>
                                                    <td><?php echo $record->class; ?></td>
                                                    <td><?php echo $date_of_birth; ?></td>
                                                    <td><?php echo $record->studentCode; ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Class</th>
                                                <th>Date Of Birth</th>
                                                <th>Student Code</th>
                                                <th>Image</th>
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