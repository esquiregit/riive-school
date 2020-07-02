<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once "classes/methods.php";
    require_once "classes/security.php";
    $_SESSION['riive_school_page'] = 'Security';
 
    if(!isset($_GET['UYww45Rfdc'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'manage-security.php';</script>";
    } else {
        $id     = Methods::validate_string($_GET['UYww45Rfdc']);
        $conn   = $pdo->open();
        $result = Security::read_security($id, $conn);
        $pdo->close();

        if(!$result) {
            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Security URL Parameters', $conn);
            die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"manage-security.php\";'><i class='fa fa-eye'></i> Yes Sir!!!</button>");
        }

        $name    = Methods::strtocapital($result->name);
        $contact = $result->contact;

        $pdo->close();
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-sm-5 align-self-center">
                    <h3 class="text-white">Edit Security</h3>
                </div>
                <div class="col-sm-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="edit-security">
                        <strong><i class='fa fa-spinner fa-spin'></i> Saving Security. Please Wait....</strong>
                    </div>
            		<div class="row inside-form">
                        <div id="slim-form">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name" id="name" value="<?php echo $name; ?>" />
                                    <input type="hidden" name="name_hidden" id="name_hidden" value="<?php echo $name; ?>" />
                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                </div>
                            </div>
    	            		<div class="col-xs-6">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" value="<?php echo $contact; ?>" />
                                    <input type="hidden" name="contact_hidden" id="contact_hidden" value="<?php echo $contact; ?>" />
                                </div>
    	            		</div>

                            <div class="col-xs-12">
    	            			<div class="form-group text-center">
                                    <button type="submit" name="editsecurity" id="editsecurity" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Save Security</button>
                                </div>
    	            		</div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>