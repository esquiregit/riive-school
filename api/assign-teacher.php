<?php
    require_once "classes/check_login.php";
    require_once "classes/check_admin.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Teachers';

    $conn     = $pdo->open();
    $teachers = Teacher::read_non_assigned_teachers_ids_and_names($conn);
    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Assign Teacher To Class</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
            	<form action="" method="POST" role="form">
                    <div class="alert alert-info alert-dismissible hide text-center font-20 m-t-10" id="assign-teacher">
                        <strong><i class='fa fa-spinner fa-spin'></i> Assigning Teacher. Please Wait....</strong>
                    </div>
            		<div class="row inside-form">
                        <div id="slim-form">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Teacher</label>
                                    <select name="teacher_id" id="teacher_id" class="form-control">
                                        <option value="default">-- Select --</option>
                                        <?php foreach($teachers as $teacher){ ?>
                                        <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
    	            		<div class="col-xs-12">
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
                            
                            <div class="col-xs-12">
    	            			<div class="form-group text-center">
                                    <button type="submit" name="assignteacher" id="assignteacher" class="btn btn-rounded btn-info"><i class="fa fa-save"></i> Assign Teacher</button>
                                </div>
    	            		</div>
                        </div>
            		</div>
            	</form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>