<?php
    require_once "classes/conn.php";
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once "classes/audit_trail.php";
    require_once "classes/methods.php";
    $_SESSION['riive_school_page'] = 'Audit Trail';

    $conn   = $pdo->open();
    $result = Audit_Trail::read_all_logs($conn);
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Activity Logs</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Audit Table" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
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
                                                <th title="Click To Sort By Username">Username</th>
                                                <th title="Click To Sort By Access Level">Access Level</th>
                                                <th title="Click To Sort By Activity">Activity</th>
                                                <th title="Click To Sort By Date">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) { ?>
                                                <tr>
                                                    <td><?php echo Methods::strtocapital($record->name); ?></td>
                                                    <td><?php echo $record->username; ?></td>
                                                    <td><?php echo $record->access_level; ?></td>
                                                    <td><?php echo Methods::strtocapital($record->activity); ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'd F Y \a\t H:i:s'); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Access Level</th>
                                                <th>Activity</th>
                                                <th>Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No Activity Logs For <?php echo $_SESSION['riive_school_name']; ?> Found
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
                                        <h4 class="text-left"><strong>Activity Logs</strong></h4>
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
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th title="Click To Sort By Name">Name</th>
                                                <th title="Click To Sort By Username">Username</th>
                                                <th title="Click To Sort By Access Level">Access Level</th>
                                                <th title="Click To Sort By Activity">Activity</th>
                                                <th title="Click To Sort By Date">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) { ?>
                                                <tr>
                                                    <td><?php echo Methods::strtocapital($record->name); ?></td>
                                                    <td><?php echo $record->username; ?></td>
                                                    <td><?php echo $record->access_level; ?></td>
                                                    <td><?php echo $record->activity; ?></td>
                                                    <td><?php echo date_format(date_create($record->date), 'd F Y \a\t H:i:s'); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Access Level</th>
                                                <th>Activity</th>
                                                <th>Date</th>
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
    
    <?php require_once "includes/footer.php"; ?>