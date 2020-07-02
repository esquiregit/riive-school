<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/visitor.php";
    require_once "classes/methods.php";
    require_once "classes/security.php";
    $_SESSION['riive_school_page'] = 'Visitors';

    $conn                   = $pdo->open();
    $visitor_name           = $error = $success = $error_message = $success_message = $visitor_result = '';
    $no_result              = false;

    if(isset($_POST['submit'])) {
        $visitor_name       = Methods::validate_string($_POST['visitor-name']);

        if(empty($visitor_name)) {
            $error          = true;
            $error_message  = 'Visitor Name Required';
        } else {
            $visitor_result = Visitor::read_visitor_by_name($visitor_name, $conn);
            $no_result      = empty($visitor_result) ? true : false;
        }
    }

    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Visitor By Name</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($visitor_result) { ?>
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
                                            <label>Visitor</label>
                                            <input type="text" class="form-control" placeholder="Visitor Name" name="visitor-name" value="<?php echo $visitor_name; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <button type="submit" name="submit" class="btn btn-rounded btn-info"><i class="fa fa-search"></i> Search Visitor</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php if($visitor_result) { ?>
                            <div class="card-body">
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name Of Visitor">Visitor Name</th>
                                                <th>Visitor Image</th>
                                                <th title="Click To Sort By Person To Visit">Person To Visit</th>
                                                <th title="Click To Sort By Clock In Time">Clock In Time</th>
                                                <th title="Click To Sort By Clock Out Time">Clock Out Time</th>
                                                <th width="25%" title="Click To Sort By Purpose">Purpose</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($visitor_result as $record) { 
                                                $name          = Methods::strtocapital($record->visitorName);
                                                $check_in_time = ($record->clockInTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockInTime), 'l d F Y \a\t H:i:s');
                                                $check_out_time = ($record->clockOutTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockOutTime), 'l d F Y \a\t H:i:s');
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                    <td><?php echo Methods::strtocapital($record->personToVisit); ?></td>
                                                    <td><?php echo $check_in_time; ?></td>
                                                    <td><?php echo $check_out_time; ?></td>
                                                    <td><?php echo $record->purposeOfVisit; ?></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-info' title='View Visit Details Of $name' onclick='location = \"view-visitor.php?mWas23=$record->id\";'><i class='fa fa-eye'></i> View Details</button>"; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Visitor Name</th>
                                                <th>Visitor Image</th>
                                                <th>Person To Visit</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Purpose</th>
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
                                    <strong>0 Results!</strong> No Visits Made By "<?php echo Methods::strtocapital($visitor_name); ?>"
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
                            <?php if($visitor_result) { ?>
                            <div class="card-body" id="printable">
                                <div style="margin-bottom: 50px;">
                                    <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>
                                            <h4 class="text-left"><strong>Visitors By Name Report</strong></h4>
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
                                                <th>Visitor Name</th>
                                                <th>Visitor Number</th>
                                                <th>Person To Visit</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Security</th>
                                                <th>Purpose</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($visitor_result as $record) { 
                                                $check_in_time  = ($record->clockInTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockInTime), 'l d F Y \a\t H:i:s');
                                                $check_out_time = ($record->clockOutTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockOutTime), 'l d F Y \a\t H:i:s');
                                                $security       = Security::read_security_name($record->securityPersonId, $conn);
                                                $image          = empty($record->imagePath) || empty($record->image) ? 'pictures/avatar.png' : $record->imagePath . $record->image;
                                            ?>
                                                <tr>
                                                    <td><?php echo Methods::strtocapital($record->visitorName); ?></td>
                                                    <td><?php echo $record->visitorNumber; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->personToVisit); ?></td>
                                                    <td><?php echo $check_in_time; ?></td>
                                                    <td><?php echo $check_out_time; ?></td>
                                                    <td><?php echo $security; ?></td>
                                                    <td><?php echo $record->purposeOfVisit; ?></td>
                                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>'s Image" title="<?php echo $name; ?>'s Image" /></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Visitor Name</th>
                                                <th>Visitor Number</th>
                                                <th>Person To Visit</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Security</th>
                                                <th>Purpose</th>
                                                <th>Image</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
    
    <?php require_once "includes/footer.php"; ?>