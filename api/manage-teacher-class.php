<?php
    require_once "classes/check_login.php";
    require_once "classes/methods.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Teachers';

    $conn   = $pdo->open();
    $result = Teacher::read_teachers_class($conn);
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Manage Teacher-Class Assignment</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($result) { ?>
                    <button title="Print Report" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
                    <?php } ?>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-0">
                            <div class="card-body">
                                <?php if($result) { ?>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name">Name</th>
                                                <th title="Click To Sort By Class">Class</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {
                                                $teacher_name = Teacher::read_teacher_name($record->teacher_id, $conn);
                                            ?>
                                                <tr>
                                                    <td><?php echo $teacher_name; ?></td>
                                                    <td><?php echo $record->class; ?></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-info' title='Edit Details' onclick='location = \"edit-assign-teacher.php?Nbvgf56Tfg=$record->id\";'><i class='fa fa-edit'></i></button>"; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No Teacher/Class Assignments Found For <?php echo $_SESSION['riive_school_name']; ?>
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
                                        <h4 class="text-left"><strong>Teachers/Classes Report</strong></h4>
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
                                                <th>Name</th>
                                                <th>Class</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {

                                            ?>
                                                <tr>
                                                    <td><?php echo $teacher_name; ?></td>
                                                    <td><?php echo $record->class; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Class</th>
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