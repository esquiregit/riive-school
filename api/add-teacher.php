<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once "classes/methods.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Add Teacher';
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Add Teacher</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="add-teacher">
                        <strong><i class='fa fa-spinner fa-spin'></i> Adding Teacher. Please Wait....</strong>
                    </div>
            		<div class="row inside-form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" placeholder="First Name" name="firstname" id="firstname" />
                            </div>
                        </div>
	            		<div class="col-md-4">
                            <div class="form-group">
                                <label>Other Name</label>
                                <input type="text" class="form-control" placeholder="Other Name" name="othername" id="othername" />
                            </div>
	            		</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" name="lastname" id="lastname" />
                            </div>
                        </div>
	            		<div class="col-md-4">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" placeholder="Email Address" name="email" id="email" />
                            </div>
	            		</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Account Type</label>
                                <select name="accountType" id="accountType" class="form-control">
                                    <option value="default">-- Select --</option>
                                    <?php foreach(Teacher::get_account_types() as $accountType){ ?>
                                    <option value="<?php echo $accountType; ?>"><?php echo $accountType; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" id="username" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Re-enter Password" name="confirm-password" id="confirm-password" />
                            </div>
                        </div>
                        <div class="col-md-12">
	            			<div class="form-group text-center">
                                <button type="submit" name="addteacher" id="addteacher" class="btn btn-rounded btn-info"><i class="fa fa-user-plus"></i> Add Teacher</button>
                            </div>
	            		</div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>