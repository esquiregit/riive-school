<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/audit_trail.php";
    require_once "classes/email.php";
    require_once "classes/methods.php";
    require_once "classes/parent.php";
    require_once "classes/student.php";
    $_SESSION['riive_school_page'] = 'Email';
    
    $conn                 = $pdo->open();
    $error                = $error_message  = $success = $success_message = $contact_number = $subject = $message = '';
    //$parents_emails       = ($_SESSION['riive_school_access_level'] == 'School Admin') ? Parents::read_parents($conn) : Parents::read_class_students_parents($conn);
    $contact_array_string = $contacts = '';
    $phone_numbers        = array();
    $classes              = Student::get_classes();
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Email Individual</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form" enctype="multipart/form-data">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="send-email">
                        <strong>Sending Email. Please Wait....</strong>
                    </div>
            		<div class="row medium-form-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" id="checkbox" checked> <span id="checkbox-label">Send To Individual</span></label>
                                </div>
                            </div>
                        </div>
	            		<?php if($_SESSION['riive_school_access_level'] == 'School Admin'){ ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label id="receiver-label">Receiver</label>
                                    <select id="phone_number" name="phone_number" class="form-control show">
                                        <option value="default">-- Select Receiver --</option>
                                        <?php foreach($classes as $classs){
                                            $full_class = ($classs == 1 || $classs == 2 || $classs == 3 || $classs == 4 || $classs == 5 || $classs == 6) ? 'Class ' . $classs : $classs;
                                            $class_list = Parents::read_parents_by_class($classs, $conn);
                                            $count      = count($class_list);
                                        ?>
                                            <optgroup label="<?php echo $full_class . ' (' . $count; echo $count == 1 ? ' Student)' : ' Students)'; ?>">
                                                <?php foreach($class_list as $contact){
                                                    $name = empty($contact->othernames) ? $contact->firstname . ' ' . $contact->lastname : $contact->firstname . ' ' . $contact->othernames . ' ' . $contact->lastname;
                                                ?>
                                                <option title="<?php echo $contact->email ?>" value="<?php echo $contact->email ?>"><?php echo Methods::strtocapital($name) . "'s " . ucfirst($contact->relation); ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>

                                    <select multiple="multiple" style="height: 150px !important" id="phone_numbers" name="phone_numbers[]" class="form-control hide">
                                        <option value="default">-- Select Receivers --</option>
                                        <?php foreach($classes as $classs){
                                            $full_class = ($classs == 1 || $classs == 2 || $classs == 3 || $classs == 4 || $classs == 5 || $classs == 6) ? 'Class ' . $classs : $classs;
                                            $class_list = Parents::read_parents_by_class($classs, $conn);
                                            $count      = count($class_list);
                                        ?>
                                            <optgroup label="<?php echo $full_class . ' (' . $count; echo $count == 1 ? ' Student)' : ' Students)'; ?>">
                                                <?php foreach(Parents::read_parents_by_class($classs, $conn) as $contact){
                                                    $name = empty($contact->othernames) ? $contact->firstname . ' ' . $contact->lastname : $contact->firstname . ' ' . $contact->othernames . ' ' . $contact->lastname;
                                                ?>
                                                <option title="<?php echo $contact->email ?>" value="<?php echo $contact->email ?>"><?php echo Methods::strtocapital($name) . "'s " . ucfirst($contact->relation); ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label id="receiver-label">Receiver</label>
                                    <select id="phone_number" name="phone_number" class="form-control show">
                                        <option value="default">-- Select Receiver --</option>
                                        <?php foreach(Parents::read_class_students_parents($conn) as $contact){
                                            $name = empty($contact->othernames) ? $contact->firstname . ' ' . $contact->lastname : $contact->firstname . ' ' . $contact->othernames . ' ' . $contact->lastname;
                                        ?>
                                        <option title="<?php echo $contact->email ?>" value="<?php echo $contact->email ?>"><?php echo Methods::strtocapital($name) . "'s " . ucfirst($contact->relation); ?></option>
                                        <?php } ?>
                                    </select>

                                    <select multiple="multiple" style="height: 150px !important" id="phone_numbers" name="phone_numbers[]" class="form-control hide">
                                        <option value="default">-- Select Receiver --</option>
                                        <?php foreach(Parents::read_class_students_parents($conn) as $contact){
                                            $name = empty($contact->othernames) ? $contact->firstname . ' ' . $contact->lastname : $contact->firstname . ' ' . $contact->othernames . ' ' . $contact->lastname;
                                        ?>
                                        <option title="<?php echo $contact->email ?>" value="<?php echo $contact->email ?>"><?php echo Methods::strtocapital($name) . "'s " . ucfirst($contact->relation); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" class="form-control" name="subject" value="<?php echo $subject; ?>" placeholder="Subject" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Attachment</label>
                                <input type="file" class="form-control" name="attachment" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Message</label>
                                <textarea class="form-control" name="message" value="<?php echo $message; ?>" placeholder='<?php echo "Type Email Here..."; ?>'><?php echo $message; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" name="sendemail" id="sendemail" class="btn btn-rounded btn-info"><i class="fa fa-paper-plane"></i> Send Email</button>
                            </div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>