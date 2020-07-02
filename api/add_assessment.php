<?php
    require_once "classes/check_login.php";
    require_once "classes/check_teacher.php";
    require_once "classes/after_nine_marking_xml.php";
    require_once "classes/assessment.php";
    require_once "classes/methods.php";
    require_once "classes/student.php";
    require_once "classes/teacher.php";
    $_SESSION['riive_school_page'] = 'Add Assessment';
    $previous_page = empty($_SERVER['HTTP_REFERER']) ? 'dashboard.php' : $_SERVER['HTTP_REFERER'];
    unset($_SESSION['riive_school_assessment_academic_year']);
    unset($_SESSION['riive_school_assessment_subject']);
    unset($_SESSION['riive_school_assessment_term']);

    $conn          = $pdo->open();
    $teacher_class = Teacher::read_assigned_class($_SESSION['riive_school_user_id'], $conn);
    if(!$teacher_class) {
        echo "<script>alert('You Have Not Been Assigned A Class');</script>";
        echo "<script>location = '$previous_page';</script>";
    } else {
        $academic_years = Student::get_academic_years();
    }

    $pdo->close();
?>
        <?php require_once "includes/header.php"; ?>
        <?php require_once "includes/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-white">Add Assessment</h3>
                </div>
                <div class="col-md-7 align-self-center">
                </div>
            </div>

            <div class="container-fluid">
                <form action="" method="POST" role="form">
                    <div class="row inside-form">
                        <div id="slim-form">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" class="form-control" placeholder="Subject" name="subject" id="subject" />
                                </div>
                            </div>
                            <div class="col-md-12 m-t-20">
                                <div class="form-group">
                                    <label>Term</label>
                                    <select class="form-control" name="term" id="term">
                                        <option value="default">-- Select --</option>
                                        <option value="First Term">First Term</option>
                                        <option value="Second Term">Second Term</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 m-t-20">
                                <div class="form-group">
                                    <label for="academic_year">Academic Year</label>
                                    <select class="form-control" name="academic_year" id="academic_year">
                                        <option value="default">-- Select --</option>
                                        <?php foreach($academic_years as $year){ ?>
                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-12 m-t-20">
                                <div class="form-group text-center">
                                    <button type="submit" name="selectassessment" id="selectassessment" class="btn btn-rounded btn-info">Enter Marks <i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    
    <?php require_once "includes/footer.php"; ?>