<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    require_once "classes/reload.php";
    $teacher_class                 = $_SESSION['riive_school_teacher_class'];
    $full_class                    = ($teacher_class == 1 || $teacher_class == 2 || $teacher_class == 3 || $teacher_class == 4 || $teacher_class == 5 || $teacher_class == 6) ? 'Class ' . $teacher_class : $teacher_class;
    $_SESSION['riive_school_page'] = $full_class . ' Students';

    $conn   = $pdo->open();
    $result = Student::read_teacher_students($teacher_class, $conn);
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
                                <?php if($result) { ?>
                                <h4 class="card-title">
                                    <button title="Print Table" class="btn btn-info pull-right" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                                </h4>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name">Name</th>
                                                <th title="Click To Sort By Gender">Gender</th>
                                                <th title="Click To Sort By Student Code">Student Code</th>
                                                <th width="10%">Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {
                                                $name   = Methods::strtocapital($record->lastname) . ' ' . Methods::strtocapital($record->firstname) . ' ' . Methods::strtocapital($record->othernames);
                                                $image  = (empty($record->imagePath) || empty($record->image)) ? 'pictures/avatar.png' : $record->imagePath . '/' . $record->image;
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->gender; ?></td>
                                                    <td><?php echo $record->studentCode; ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-success' title='View $name' onclick='location = \"view-student.php?Rfd5Tf=$record->studentid\";'><i class='fa fa-eye'></i></button>"; ?>
                                                        </div>
                                                    </td>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Student Code</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> You Haven't Been Assigned A Class Yet
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
                                            <h4 class="text-left"><strong>Students Report</strong></h4>
                                        </td>
                                        <td>
                                            <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                                <?php if($result) { ?>
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
                                            <?php foreach ($result as $record) {
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