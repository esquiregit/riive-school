<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Add Student';
    $default_image = 'pictures/avatar.png';
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Add Student</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form" enctype="multipart/form-data">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="add-student">
                        <strong><i class='fa fa-spinner fa-spin'></i> Adding Student. Please Wait....</strong>
                    </div>
            		<div class="row inside-form">
            			<div class="col-md-6">
	            			<div class="form-group">
                                <div class="profile-image" title="Profile Picture">
                                	<img id="profile-image" src="<?php echo $default_image; ?>" alt="Select Profile Picture" class="profile-image" />
                                </div>
                            </div>
	            		</div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" class="form-control" name="profile-image" src="$image_name" onchange="loadImage(this);" />
                                <input type="hidden" name="access_level" id="access_level" value="<?php echo $_SESSION['riive_school_access_level']; ?>" />
                            </div>
	            			<div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" placeholder="First Name" name="firstname" id="firstname" />
                            </div>
                            <div class="form-group">
                                <label>Other Name</label>
                                <input type="text" class="form-control" placeholder="Other Name" name="othername" id="othername" />
                            </div>
	            		</div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" name="lastname" id="lastname" />
                            </div>
	            		</div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="default">-- Select --</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
	            		</div>
                        <?php if($_SESSION['riive_school_access_level'] == 'School Admin'){?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Class</label>
                                <select name="class" id="class" class="form-control">
                                    <option value="default">-- Select --</option>
                                    <?php foreach(Student::get_classes() as $class){ ?>
                                    <option value="<?php echo $class; ?>"><?php echo $class; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <?php } else { ?>
                            <input type="hidden" name="class" id="class" value="<?php echo $_SESSION['riive_school_teacher_class']; ?>" />
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Of Birth</label>
                                <input type="date" class="form-control" placeholder="Date Of Birth" name="dob" id="dob" max="<?php echo Date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
	            			<div class="form-group text-center">
                                <button type="submit" name="addstudent" id="addstudent" class="btn btn-rounded btn-info"><i class="fa fa-user-plus"></i> Add Student</button>
                            </div>
	            		</div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>