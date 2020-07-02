<?php
    require_once "classes/security.php";
    require_once "classes/check_login.php";
    require_once "classes/methods.php";
    $_SESSION['riive_school_page'] = 'Security';

    $conn            = $pdo->open();
    $security_id     = $error = $success = $error_message = $success_message = $approvals_result = $security_person = '';
    $no_result       = false;
    $security_result = Security::read_securities_info($conn);

    if(isset($_POST['submit'])) {
        $security_id          = Methods::validate_string($_POST['security-code']);

        if(empty($security_id) || $security_id == 'default') {
            $error            = true;
            $error_message    = 'Please Select A Security Personnel';
        } else {
            $approvals_result = Security::read_security_person_approvals($security_id, $conn);
            $security_person  = Security::read_security_name($security_id, $conn);
            $no_result        = empty($approvals_result) ? true : false;
        }
    }

    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Approvals By Security Personnel</h3>
                </div>
                <div class="col-md-7 text-right">
                    <?php if($approvals_result) { ?>
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
                                            <label>Security Personnel</label>
                                        <?php if($security_result){ ?>
                                            <select name="security-code" class="form-control">
                                                <option value="default" <?php if(isset($security_id) && $security_id == 'default') echo 'selected'; ?>>-- Select Security Personnel --</option>
                                                <?php foreach($security_result as $security){ ?>
                                                <option value="<?php echo $security->id ?>" <?php if(isset($security_id) && $security_id == $security->id) echo 'selected'; ?>><?php echo Methods::strtocapital($security->name) ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <select name="security-code" class="form-control">
                                                <option value="default">-- No Security Personnel --</option>
                                            </select>
                                        <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-center">
                                            <button type="submit" name="submit" class="btn btn-rounded btn-info"><i class="fa fa-search"></i> Search Approvals</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php if($approvals_result) { ?>
                            <div class="card-body">
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name Of Visitor">Visitor Name</th>
                                                <th title="Click To Sort By Person To Visit">Person To Visit</th>
                                                <th title="Click To Sort By Visitor Phone Number">Visitor Number</th>
                                                <th title="Click To Sort By Clock In Time">Clock In Time</th>
                                                <th title="Click To Sort By Clock Out Time">Clock Out Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($approvals_result as $record) { 
                                                $name           = Methods::strtocapital($record->visitorName);
                                                $check_out_time = ($record->clockOutTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockOutTime), 'l d F Y \a\t H:i:s');
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->personToVisit; ?></td>
                                                    <td><?php echo $record->visitorNumber; ?></td>
                                                    <td><?php echo date_format(date_create($record->clockInTime), 'l d F Y \a\t H:i:s'); ?></td>
                                                    <td><?php echo $check_out_time; ?></td>
                                                    <td>
                                                    <div>
                                                        <?php echo "<button class='btn btn-info' title='View Visit Details Of $name' onclick='location = \"view-security-visitor.php?mWas23=$record->id\";'><i class='fa fa-eye'></i> View Details</button>"; ?>
                                                    </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Visitor Name</th>
                                                <th>Person To Visit</th>
                                                <th>Visitor Number</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
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
                                    <strong>0 Results!</strong> No Approvals Found For <?php echo Methods::strtocapital(Security::read_security_name($security_id, $conn)); ?>
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
                            <?php if($approvals_result) { ?>
                            <div class="card-body" id="printable">
                                <div style="margin-bottom: 50px;">
                                    <img src="images/riive.png" alt="RiiVe Logo" style="display:block;margin:auto;width:50%;height:100%;" />
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>
                                            <h4 class="text-left"><strong>Approvals By <?php echo $security_person; ?></strong></h4>
                                        </td>
                                        <td>
                                            <h4 class="text-right">Date: <?php echo '<strong>' . date_format(date_create(Date("Y-m-d H:i:s")), 'd F Y \a\t H:i:s') . '</strong>'; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                                <h4 class="card-title"></h4>
                                <div class="table-responsive m-t-40">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Visitor Name</th>
                                                <th>Person To Visit</th>
                                                <th>Visitor Number</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Purpose</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($approvals_result as $record) { 
                                                $name           = Methods::strtocapital($record->visitorName);
                                                $check_out_time = ($record->clockOutTime == '0000-00-00 00:00:00') ? 'Not Yet' : date_format(date_create($record->clockOutTime), 'l d F Y \a\t H:i:s');
                                            ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td><?php echo $record->personToVisit; ?></td>
                                                    <td><?php echo $record->visitorNumber; ?></td>
                                                    <td><?php echo date_format(date_create($record->clockInTime), 'l d F Y \a\t H:i:s'); ?></td>
                                                    <td><?php echo $check_out_time; ?></td>
                                                    <td><?php echo $record->purposeOfVisit; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Visitor Name</th>
                                                <th>Person To Visit</th>
                                                <th>Visitor Number</th>
                                                <th>Clock In Time</th>
                                                <th>Clock Out Time</th>
                                                <th>Purpose</th>
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