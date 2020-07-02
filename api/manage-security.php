<?php
    require_once "classes/check_login.php";
    require_once 'classes/audit_trail.php';
    require_once "classes/methods.php";
    require_once "classes/security.php";
    $_SESSION['riive_school_page'] = 'Security';

    $conn   = $pdo->open();
    $result = Security::read_all_securities($conn);
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">View Security Personnel</h3>
                </div>
                <div class="col-md-7 text-right">
                    <button title="Print Security Info" class="btn btn-info" onclick="printReport();"><i class="fa fa-print"></i> Print</button>
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
                                                <th title="Click To Sort By Contact Number">Contact Number</th>
                                                <th title="Click To Sort By Status">Account Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) { ?>
                                                <tr>
                                                    <td><?php echo $record->name; ?></td>
                                                    <td><?php echo $record->contact; ?></td>
                                                    <td><?php echo $record->accountType; ?></td>
                                                    <td>
                                                        <div>
                                                        <?php echo "<button class='btn btn-info' title='Edit Details' onclick='location = \"edit-security.php?UYww45Rfdc=$record->id\";'><i class='fa fa-edit'></i></button>"; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Account Type</th>
                                                <th>Contact Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="alert alert-info alert-dismissible fade show text-center font-20">
                                        <strong>0 Results!</strong> No <?php echo $_SESSION['riive_school_page'] . ' Personnel '; ?> Found For <?php echo $_SESSION['riive_school_name']; ?>
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
                                            <h4 class="text-left"><strong>Security Personnel</strong></h4>
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
                                                <th>Name</th>
                                                <th>Contact Number</th>
                                                <th>Account Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $record) { ?>
                                                <tr>
                                                    <td><?php echo $record->name; ?></td>
                                                    <td><?php echo $record->contact; ?></td>
                                                    <td><?php echo $record->accountType; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Contact Number</th>
                                                <th>Account Type</th>
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