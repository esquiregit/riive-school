<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/methods.php";
    require_once "classes/parent.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Parents';


    $conn   = $pdo->open();
    if($_SESSION['riive_school_access_level'] == 'Teacher') {
        $status = Student::read_teacher_students($_SESSION['riive_school_teacher_class'], $conn);
    } else {
        $status = true;
    }
    $result = Parents::read_class_students_parents($conn);
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Parents</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($status && $result) { ?>
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                    <?php } ?>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <div class="card-body">
                                <?php if($status && $result) { ?>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Parent">Parent</th>
                                                <th title="Click To Sort By Student">Student</th>
                                                <th title="Click To Sort By Relation">Relation</th>
                                                <th title="Click To Sort By Phone">Phone</th>
                                                <th title="Click To Sort By Status">Status</th>
                                                <?php if($_SESSION['riive_school_access_level'] == "School Admin") { ?>
                                                <th>Action</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {
                                                $student = Methods::strtocapital($record->firstname . ' ' . $record->othernames . ' ' . $record->lastname);
                                                $status  = $record->status == 1 || $record->status == 'Active' ? 'Active' : 'Inactive';
                                            ?>
                                                <tr>
                                                    <td><?php echo Methods::strtocapital($record->fullname); ?></td>
                                                    <td><?php echo $student; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->relation); ?></td>
                                                    <td><?php echo $record->phone; ?></td>
                                                    <td><?php echo $status; ?></td>
                                                    <?php if($_SESSION['riive_school_access_level'] == "School Admin") { ?>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-info' title='View Details' onclick='location = \"view-parent.php?kIju87g=$record->parentid\";'><i class='fa fa-eye'></i> View</button>"; ?>
                                                        </div>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Parent</th>
                                                <th>Student</th>
                                                <th>Relation</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <?php if($_SESSION['riive_school_access_level'] == "School Admin") { ?>
                                                <th>Action</th>
                                                <?php } ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No <?php echo $_SESSION['riive_school_page']; ?> To Display
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
                        <div class="card mt-0" id="printable">
                            <div style="margin-bottom: 50px;">
                                <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                            </div>
                            <table class="table">
                                <tr>
                                    <td>
                                        <h4 class="text-left"><strong>Parents Report</strong></h4>
                                    </td>
                                    <td>
                                        <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                    </td>
                                </tr>
                            </table>
                            <div class="card-body">
                                <?php if($result) { ?>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Parent">Parent</th>
                                                <th title="Click To Sort By Student">Student</th>
                                                <th title="Click To Sort By Relation">Relation</th>
                                                <th title="Click To Sort By Phone">Phone</th>
                                                <th title="Click To Sort By Status">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {
                                                $student = Methods::strtocapital($record->firstname . ' ' . $record->othernames . ' ' . $record->lastname);
                                                $status  = $record->status == 1 || $record->status == 'Active' ? 'Active' : 'Inactive';
                                            ?>
                                                <tr>
                                                    <td><?php echo Methods::strtocapital($record->fullname); ?></td>
                                                    <td><?php echo $student; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->relation); ?></td>
                                                    <td><?php echo $record->phone; ?></td>
                                                    <td><?php echo $status; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Parent</th>
                                                <th>Student</th>
                                                <th>Relation</th>
                                                <th>Phone</th>
                                                <th>Status</th>
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