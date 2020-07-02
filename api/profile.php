<?php
    require_once "classes/check_login.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/admin.php";
    require_once "classes/methods.php";
    require_once "classes/school.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Profile';

    $conn = $pdo->open();
    if($_SESSION['riive_school_access_level'] == 'School Admin') {
        $result     = School::read_school($_SESSION['riive_school_id'], $conn);
        $regions    = School::read_regions($conn);
        $user_id    = $_SESSION['riive_school_id'];
        $schoolname = $result->schoolname;
        $email      = $result->email;
        $location   = $result->location;
        $phone      = $result->phone;
        $region     = $result->region;
        $website    = $result->website;
        $username   = $result->username;
        $email      = $result->email;
        $image      = $result->image;
    } else if($_SESSION['riive_school_access_level'] == 'Teacher') {
        $result     = Teacher::read_teacher_two($_SESSION['riive_school_user_id'], $conn);
        $user_id    = $_SESSION['riive_school_user_id'];
        $name       = $result->name;
        $username   = $result->username;
        $email      = $result->email;
        $contact    = $result->contact;
        $image      = $result->image;
    }

    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Profile</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form" enctype="multipart/form-data">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="update-profile">
                        <strong><i class='fa fa-spinner fa-spin'></i> Updating Profile. Please Wait....</strong>
                    </div>
                    <?php if($_SESSION['riive_school_access_level'] == 'School Admin') { ?>
                    <div class="row inside-form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="profile-image" title="Profile Picture">
                                    <img id="profile-image" src="<?php echo $image; ?>" alt="Image" class="profile-image" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" class="form-control" name="profile-image" src="$image_name" onchange="loadImage(this);" />
                                <input type="hidden" name="access_level" id="access_level" value="<?php echo $_SESSION['riive_school_access_level']; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
                                <input type="text" class="form-control" placeholder="First Name" name="schoolname" id="schoolname" value="<?php echo $schoolname; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" placeholder="Email Address" name="email" id="email" value="<?php echo $email; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" class="form-control" placeholder="Location" name="location" id="location" value="<?php echo $location; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" placeholder="Phone Number" name="phone" id="phone" value="<?php echo $phone; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Region</label>
                                <select id="region" name="region" class="form-control">
                                    <option value="default" <?php if(isset($region) && $region == 'default') echo 'selected'; ?>>-- Select --</option>
                                    <?php foreach($regions as $reg){ ?>
                                        <option value="<?php echo $reg->regionName; ?>" <?php if(isset($region) && $region == $reg->regionName) echo 'selected'; ?>><?php echo Methods::strtocapital($reg->regionName); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="url" class="form-control" placeholder="Website" name="website" id="website" value="<?php echo $website; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Re-Enter Password" name="confirm_password" id="confirm_password" />
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <label class="inv">Submit</label>
                            <div class="form-group">
                                <button type="submit" name="editprofileschool" id="editprofileschool" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Save Profile</button>
                            </div>
                        </div>
                    </div>
                    <?php } else if($_SESSION['riive_school_access_level'] == 'Teacher') { ?>
            		<div class="row inside-form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="profile-image" title="Profile Picture">
                                    <img id="profile-image" src="<?php echo $image; ?>" alt="Image" class="profile-image" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" class="form-control" name="profile-image" src="$image_name" onchange="loadImage(this);" />
                                <input type="hidden" name="access_level" id="access_level" value="<?php echo $_SESSION['riive_school_access_level']; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
                                <input type="text" class="form-control" placeholder="Name" name="name" id="name" value="<?php echo $name; ?>" />
                            </div>
                        </div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control" placeholder="Email Address" name="email" id="email" value="<?php echo $email; ?>" />
                            </div>
	            		</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" placeholder="Contact Number" name="contact" id="contact" value="<?php echo $contact; ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $username; ?>" />
                            </div>
                        </div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                            </div>
	            		</div>
	            		<div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Re-Enter Password" name="confirm_password" id="confirm_password" />
                            </div>
	            		</div>
	            		<div class="col-md-12 text-center">
	            			<label class="inv">Submit</label>
	            			<div class="form-group">
                                <button type="submit" name="editprofileteacher" id="editprofileteacher" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Save Profile</button>
                            </div>
	            		</div>
            		</div>
                    <?php } ?>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>