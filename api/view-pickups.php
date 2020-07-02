<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/methods.php";
    require_once "classes/pickup.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Pickups';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];

    $conn           = $pdo->open();
    @$teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
    if(!$teacher_class && $_SESSION['riive_school_access_level'] == 'Teacher') {
        echo "<script>alert('You Have Not Been Assigned A Class');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $result = Pickup::read_pickups($conn);
    }
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View All Pickup</h3>
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
                                                <th title="Click To Sort By Pickup Person">Pickup Person</th>
                                                <th title="Click To Sort By Student">Student</th>
                                                <th title="Click To Sort By Code">Code</th>
                                                <th title="Click To Sort By Date">Date</th>
                                                <th title="Click To Sort By Sent By">Sent By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) { $name = Methods::strtocapital($record->pickUpPerson); ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->firstname . ' ' . $record->othernames . ' ' . $record->lastname); ?></td>
                                                    <td><?php echo $record->code; ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'l d F Y \a\t H:i:s'); ?></td>
                                                    <td><?php echo Methods::strtocapital($record->fullname); ?></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-info' title='View $name' onclick='location = \"view-pickup.php?AvgFvc=$record->id\";'><i class='fa fa-eye'></i> View</button>"; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Pickup Person</th>
                                                <th>Student</th>
                                                <th>Code</th>
                                                <th>Date</th>
                                                <th>Sent By</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No <?php echo $_SESSION['riive_school_page']; ?> Found
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
                                        <h4 class="text-left"><strong>Pickup Report</strong></h4>
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
                                                <th>Pickup Person</th>
                                                <th>Pickup Type</th>
                                                <th>Pickup Number</th>
                                                <th>Student</th>
                                                <th>Code</th>
                                                <th>Date</th>
                                                <th>Sent By</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) {
                                                $name  = Methods::strtocapital($record->pickUpPerson);
                                                $image = empty($record->imagePath) || empty($record->image) ? 'pictures/avatar.png' : $record->imagePath . $record->image;
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->pickUpType; ?></td>
                                                    <td><?php echo $record->phone; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->firstname . ' ' . $record->othernames . ' ' . $record->lastname); ?></td>
                                                    <td><?php echo $record->code; ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'l d F Y \a\t H:i:s'); ?></td>
                                                    <td><?php echo Methods::strtocapital($record->fullname); ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Pickup Person</th>
                                                <th>Pickup Type</th>
                                                <th>Pickup Number</th>
                                                <th>Student</th>
                                                <th>Code</th>
                                                <th>Date</th>
                                                <th>Sent By</th>
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