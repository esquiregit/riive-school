<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once "classes/audit_trail.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Teachers';
 
    if(!isset($_GET['Nbvgf56Tfg'])){
        echo "<script>alert('Operation Failed. Please Try Again');</script>";
        echo "<script>location = 'view-teachers.php';</script>";
    } else {
        $id     = Methods::validate_string($_GET['Nbvgf56Tfg']);
        $conn   = $pdo->open();
        $result = Teacher::read_teacher_class($id, $conn);
        $pdo->close();

        if(!$result) {
            Audit_Trail::create_log($_SESSION['riive_school_id'], $_SESSION['riive_school_name'], $_SESSION['riive_school_username'], $_SESSION['riive_school_access_level'], 'Tried To Change View Teacher URL Parameters', $conn);
            die("Please Don't Try To Be Smart....<br /><br /><button class='btn btn-info' onclick='location = \"manage-teacher-class.php\";'><i class='fa fa-eye'></i> Yes Sir!!!</button>");
        }

        $teachers     = Teacher::read_teachers_ids_and_names($conn);
        $teacher_id   = $result->teacher_id;
        $teacher_name = Teacher::read_teacher_name($teacher_id, $conn);
        $class        = $result->class;

        $pdo->close();
    }
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Edit Teacher/Class Assignment</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="edit-assign-teacher">
                        <strong><i class='fa fa-spinner fa-spin'></i> Saving Assignment. Please Wait....</strong>
                    </div>
            		<div class="row inside-form">
                        <div id="slim-form">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Teacher</label>
                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                    <input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo $teacher_id; ?>" />
                                    <input type="hidden" name="teacher_id_hidden" id="teacher_id_hidden" value="<?php echo $teacher_id; ?>" />
                                    <input type="text" class="form-control" readonly="readonly" name="teacher_name" id="teacher_name" value="<?php echo $teacher_name; ?>" />
                                </div>
                            </div>
    	            		<div class="col-xs-12">
                                <div class="form-group">
                                    <label>Class</label>
                                    <input type="hidden" name="class_hidden" id="class_hidden" value="<?php echo $class; ?>" />
                                    <select name="class" id="class" class="form-control">
                                        <option value="default" <?php if(isset($class) && $class == 'default'){echo 'selected';}?>>-- Select --</option>
                                        <?php foreach(Student::get_classes() as $classs){ ?>
                                        <option value="<?php echo $classs; ?>" <?php if(isset($class) && $class == $classs){echo 'selected';}?>><?php echo $classs; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
    	            		</div>
                            
                            <div class="col-xs-12">
    	            			<div class="form-group text-center">
                                    <button type="submit" name="editassignteacher" id="editassignteacher" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Save</button>
                                </div>
    	            		</div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>