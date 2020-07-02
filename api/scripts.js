$(document).ready(function(){

    /*-- Assessment --*/
    $("#selectassessment").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/assessment_selection_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();

                    window.location = 'add-assessment.php';
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#addassessment").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        var answer   = confirm('Are You Sure You Want To Submit Assessment?');

        if(answer) {
            $("#add-assessment").fadeIn("1500");
            $("#addassessment").html("<i class='fa fa-spinner fa-spin'></i> Adding Assessment....");

            //$('form :input').prop("disabled", true);
            $.ajax({
                url: 'classes/add_assessment_xml.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response, status) {
                    $("#add-assessment").hide();
                    $("#addassessment").html("<i class='fa fa-file-medical'></i> Submit Assessment");
                    if(response[0]['status'] == "Success") {
                        $("form")[0].reset();
                        $("html, body").animate({scrollTop: 0}, "slow");
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'add_assessment.php'; }, 1550);
                    } else if(response[0]['status'] == "Warning") {
                        display_warning(response[0]['message'], response[0]['status']);
                    } else {
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        }
    });

    $("#editassessment").click(function(event) {
        event.preventDefault();
        var id                        = $("#id").val();
        var class_tests               = $("#class_tests").val();
        var assignments               = $("#assignments").val();
        var interim_assessment        = $("#interim_assessment").val();
        var attendance_mark           = $("#attendance_mark").val();
        var exams_score               = $("#exams_score").val();
        var class_tests_hidden        = $("#class_tests_hidden").val();
        var assignments_hidden        = $("#assignments_hidden").val();
        var interim_assessment_hidden = $("#interim_assessment_hidden").val();
        var attendance_mark_hidden    = $("#attendance_mark_hidden").val();
        var exams_score_hidden        = $("#exams_score_hidden").val();

        if(class_tests != class_tests_hidden || assignments != assignments_hidden || interim_assessment != interim_assessment_hidden || attendance_mark != attendance_mark_hidden || exams_score != exams_score_hidden) {
            var formData = new FormData($('form')[0]);
            $("#edit-assessment").fadeIn("1500");
            $("#editassessment").html("<i class='fa fa-spinner fa-spin'></i> Saving Assessment....");

            $.ajax({
                url: 'classes/edit_assessment_xml.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response, status) {
                    $("#edit-assessment").hide();
                    $("#editassessment").html("<i class='fa fa-save'></i> Save Assessment");
                    if(response[0]['status'] == "Success") {
                        $("html, body").animate({scrollTop: 0}, "slow");
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'manage-assessments.php'; }, 1550);
                    } else if(response[0]['status'] == "Warning") {
                        display_warning(response[0]['message'], response[0]['status']);
                    } else {
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        } else {
            display_info("No Change Made So Nothing To Save", "No Change");
        }
    });

    $("#class_tests").change(function(event) {
        var class_tests        = parseFloat($(this).val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#class_tests").keyup(function(event) {
        var class_tests        = parseFloat($(this).val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#assignments").change(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($(this).val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#assignments").keyup(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($(this).val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#interim_assessment").change(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($(this).val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#interim_assessment").keyup(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($(this).val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#attendance_mark").change(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($(this).val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#attendance_mark").keyup(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($(this).val());
        var exams_score        = parseFloat($("#exams_score").val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#exams_score").change(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($(this).val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    $("#exams_score").keyup(function(event) {
        var class_tests        = parseFloat($("#class_tests").val());
        var assignments        = parseFloat($("#assignments").val());
        var interim_assessment = parseFloat($("#interim_assessment").val());
        var attendance_mark    = parseFloat($("#attendance_mark").val());
        var exams_score        = parseFloat($(this).val());

        get_total_score_grade_and_remarks(class_tests, assignments, interim_assessment, attendance_mark, exams_score);
    });

    /*-- Attendance --*/
    $(".form-check-input").click(function(event) {
        if($(this).val() == 'Present') {
            $(this).val("Absent");
        } else {
            $(this).val("Present");
        }
    });
    
    $("#markattendance").click(function(event) {
        event.preventDefault();
        var answer = confirm('Are You Sure You Want To Submit Attendance?');
        if(answer) {
            var formData = new FormData($('form')[0]);
            $("#mark-attendance").fadeIn("1500");
            $("#markattendance").html("<i class='fa fa-spinner fa-spin'></i> Marking Attendance....");

            $.ajax({
                url: 'classes/mark_attendance_xml.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response, status) {
                    $("#mark-attendance").hide();
                    $("#markattendance").html("<i class='fa fa-save'></i> Submit Attendance");
                    if(response[0]['status'] == "Success") {
                        $("form")[0].reset();
                        $("html, body").animate({scrollTop: 0}, "slow");
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'mark-attendance.php'; }, 1550);
                    } else {
                        $("html, body").animate({scrollTop: 0}, "slow");
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        }
    });

    $("#selectsubjectandclass").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/attendance_marking_selection_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();

                    window.location = 'mark-attendance.php';
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#selectattendance").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/fetch_attendance_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                if(response[0]['status'] == "Success") {
                    window.location = 'manage-attendance.php';
                } else {
                    display_error(response[0]['result'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editattendance").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/edit_attendance_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                if(response[0]['status'] == "Success") {
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = '.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    /*-- Student --*/
    $("#addstudent").click(function(event) {
        event.preventDefault();
        var formData     = new FormData($('form')[0]);
        var access_level = $("#access_level").val();
        $("#add-student").fadeIn("1500");
        $("#addstudent").html("<i class='fa fa-spinner fa-spin'></i> Adding Student....");

        $.ajax({
            url: 'classes/add_student_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#add-student").hide();
                $("#addstudent").html("<i class='fa fa-user-plus'></i> Add Student");
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    $("html, body").animate({scrollTop: 0}, "slow");
                    display_success_short(response[0]['message'], response[0]['status']);
                    if(access_level == 'School Admin')
                        setTimeout(function(){ window.location.href = 'manage-students.php'; }, 1550);
                    else
                        setTimeout(function(){ window.location.href = 'view-students.php'; }, 1550);
                } else if(response[0]['status'] == "Warning1") {
                    $("html, body").animate({scrollTop: 0}, "slow");
                    display_warning(response[0]['message'], "Warning");
                } else if(response[0]['status'] == "Warning2") {
                    $("html, body").animate({scrollTop: 800}, "slow");
                    display_warning(response[0]['message'], "Warning");
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editstudent").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#edit-student").fadeIn("1500");
        $("#editstudent").html("<i class='fa fa-spinner fa-spin'></i> Saving Details....");

        $.ajax({
            url: 'classes/edit_student_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#edit-student").hide();
                $("#editstudent").html("<i class='fa fa-save'></i> Save Student Details");
                if(response[0]['status'] == "Success") {
                    $("html, body").animate({scrollTop: 0}, "slow");
                    toCapitalCase($("#firstname"));
                    toCapitalCase($("#lastname"));
                    toCapitalCase($("#othername"));
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'manage-students.php'; }, 1550);
                } else if(response[0]['status'] == "Warning1") {
                    $("html, body").animate({scrollTop: 100}, "slow");
                    display_warning(response[0]['message'], "Warning");
                } else if(response[0]['status'] == "Warning2") {
                    $("html, body").animate({scrollTop: 800}, "slow");
                    display_warning(response[0]['message'], "Warning");
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#searchstudentbyclass").click(function(event) {
        event.preventDefault();
        var student_class = $("#student-class").val();

        if(student_class != 'default') {
            $.ajax({
                url: 'classes/get_student_by_class_xml.php',
                type: 'POST',
                data: {student_class: student_class},
                dataType: 'json',
                success: function(response, status) {
                    var length    = response.length;
                    if(length > 0) {
                        $("#card-table").fadeIn("3000");
                        $("#card-notable").fadeOut("3000");
                        $("#print").fadeIn("3000");

                        $("#student-class").empty();
                        for(var index = 0; index < length; index++) {
                            var name = response[index]['name'];
                            var gender = response[index]['gender'];
                            var classs = response[index]['class'];
                            var studentCode = response[index]['studentCode'];
                            var image = response[index]['image'];
                            var dob = response[index]['dob'];

                            $("#tbody").empty();
                            $("#tbody").append("<tr>");
                            $("#tbody").append("<td>"+name+"</td>");
                            $("#tbody").append("<td>"+gender+"</td>");
                            $("#tbody").append("<td>"+classs+"</td>");
                            $("#tbody").append("<td>"+studentCode+"</td>");
                            $("#tbody").append("<td>"+name+"</td>");
                            $("#tbody").append("<td>"+gender+"</td>");
                                /*<td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><img src="" alt="'s Image" title="'s Image" /></td>
                                <td>
                                    <div>
                                    <?php echo "<button class='btn btn-success' title='View $name' onclick='location = \"view-student.php?Rfd5Tf=$record->studentid\";'><i class='fa fa-eye'></i></button>"; ?>
                                    <?php echo "<button class='btn btn-info' title='Edit $name' onclick='location = \"edit-student.php?Rfd5Tf=$record->studentid\";'><i class='fa fa-edit'></i></button>"; ?>
                                    </div>
                                </td>*/
                            $("#tbody").append("</tr>");
                        }
                    } else {
                        $("#card-table").fadeOut("3000");
                        $("#card-notable").fadeIn("3000");
                        $("#print").fadeOut("3000");
                    }

                    /*$("#student-class").empty();
                    $("#student-class").append("<option value = 'default'>-- Select Region --</option>");

                    for(var index = 0; index < length; index++) {
                        var id    = response[index]['id'];
                        var name  = response[index]['region_name'];

                        $("#student-class").append("<option value = '" + id + "'>" + name + "</option>");
                    }*/
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        } else {
            $("#card-table").fadeOut("3000");
            $("#card-notable").fadeOut("3000");
            $("#print").fadeOut("3000");
        }
    });

    /*-- Teacher --*/    
    $("#addteacher").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#add-teacher").fadeIn("1500");
        $("#addteacher").html("<i class='fa fa-spinner fa-spin'></i> Adding Teacher....");

        $.ajax({
            url: 'classes/add_teacher_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#add-teacher").hide();
                $("#addteacher").html("<i class='fa fa-user-plus'></i> Add Teacher");
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'manage-teachers.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editteacher").click(function(event) {
        event.preventDefault();
        var id                 = $("#id").val();
        var name               = $("#name").val();
        var email              = $("#email").val();
        var contact            = $("#contact").val();
        var accountType        = $("#accountType").val();
        var status             = $("#status").val();
        var name_hidden        = $("#name_hidden").val();
        var email_hidden       = $("#email_hidden").val();
        var contact_hidden     = $("#contact_hidden").val();
        var accountType_hidden = $("#accountType_hidden").val();
        var status_hidden      = $("#status_hidden").val();

        if(name != name_hidden || email != email_hidden || contact != contact_hidden || accountType != accountType_hidden || status != status_hidden) {
            var formData = new FormData($('form')[0]);
            $("#edit-teacher").fadeIn("1500");
            $("#editteacher").html("<i class='fa fa-spinner fa-spin'></i> Saving Teacher....");

            $.ajax({
                url: 'classes/edit_teacher_xml.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response, status) {                
                    $("#edit-teacher").hide();
                    $("#editteacher").html("<i class='fa fa-save'></i> ASavedd Teacher");

                    if(response[0]['status'] == "Success") {
                        toCapitalCase($("#name"));
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'manage-teachers.php'; }, 1550);
                    } else if(response[0]['status'] == "Warning") {
                        display_warning(response[0]['message'], response[0]['status']);
                    } else {
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        } else {
            display_info("No Change Made So Nothing To Save", "No Change");
        }
    });

    $("#assignteacher").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#assign-teacher").fadeIn("1500");
        $("#assignteacher").html("<i class='fa fa-spinner fa-spin'></i> Assigning Teacher....");

        $.ajax({
            url: 'classes/assign_teacher_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#assign-teacher").hide();
                $("#assignteacher").html("<i class='fa fa-save'></i> Assign Teacher");
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'manage-teacher-class.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editassignteacher").click(function(event) {
        event.preventDefault();
        //var formData = new FormData($('form')[0]);
        var id                = $("#id").val();
        var teacher_id        = $("#teacher_id").val();
        var classs            = $("#class").val();
        var teacher_id_hidden = $("#teacher_id_hidden").val();
        var class_hidden      = $("#class_hidden").val();

        if(classs != class_hidden) {
            $("#edit-assign-teacher").fadeIn("1500");
            $("#editassignteacher").html("<i class='fa fa-spinner fa-spin'></i> Saving....");
            /*$.ajax({
                url: 'classes/edit_assigned_teacher_xml.php',
                type: 'POST',
                data: {
                        id        : id,
                        teacher_id: teacher_id,
                        classs    : classs
                    },
                dataType: 'json',
                success: function(response, status) {
                    $("#edit-assign-teacher").hide();
                    $("#editassignteacher").html("<i class='fa fa-save'></i> Save");
                    if(response[0]['status'] == "Success") {
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'manage-teacher-class.php'; }, 1550);
                    } else if(response[0]['status'] == "Warning") {
                        display_warning(response[0]['message'], response[0]['status']);
                    } else {
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });*/
        } else {
            display_info("No Change Made So Nothing To Save", "No Change");
        }
    });

    /*-- Security --*/
    $("#addsecurity").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#add-security").fadeIn("1500");
        $("#addsecurity").html("<i class='fa fa-spinner fa-spin'></i> Adding Security....");

        $.ajax({
            url: 'classes/add_security_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {                
                $("#add-security").hide();
                $("#addsecurity").html("<i class='fa fa-user-plus'></i> Add Security");
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'manage-security.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editsecurity").click(function(event) {
        event.preventDefault();
        var id             = $("#id").val();
        var name           = $("#name").val();
        var contact        = $("#contact").val();
        var name_hidden    = $("#name_hidden").val();
        var contact_hidden = $("#contact_hidden").val();

        if(name != name_hidden || contact != contact_hidden) {
            var formData = new FormData($('form')[0]);
            $("#edit-security").fadeIn("1500");
            $("#editsecurity").html("<i class='fa fa-spinner fa-spin'></i> Saving Security....");

            $.ajax({
                url: 'classes/edit_security_xml.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response, status) {              
                    $("#edit-security").hide();
                    $("#editsecurity").html("<i class='fa fa-save'></i> Save Security");
                    if(response[0]['status'] == "Success") {
                        toCapitalCase($("#name"));
                        display_success_short(response[0]['message'], response[0]['status']);
                        setTimeout(function(){ window.location.href = 'manage-security.php'; }, 1550);
                    } else if(response[0]['status'] == "Warning") {
                        display_warning(response[0]['message'], response[0]['status']);
                    } else {
                        display_error(response[0]['message'], response[0]['status']);
                    }
                }, 
                error: function (request, xhr, thrownError) {
                    alert(request.responseText + " " + xhr.status + " " + thrownError);
                }
            });
        } else {
            display_info("No Change Made So Nothing To Save", "No Change");
        }
    });

    /*-- Profile --*/
    $("#editprofileschool").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#update-profile").fadeIn("1500");
        $("#editprofileschool").html("<i class='fa fa-spinner fa-spin'></i> Updating Profile....");
        
        $.ajax({
            url: 'classes/save_profile_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#update-profile").hide();
                $("#editprofileschool").html("<i class='fa fa-save'></i> Save Profile");
                if(response[0]['status'] == "Success") {
                    toCapitalCase($("#schoolname"));
                    $("#email").val($("#email").val().toLowerCase());
                    toCapitalCase($("#location"));
                    $("#website").val($("#website").val().toLowerCase());
                    $("#password").val("");
                    $("#confirm_password").val("");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'dashboard.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#editprofileteacher").click(function(event) {
        event.preventDefault();
        var formData = new FormData($('form')[0]);
        $("#update-profile").fadeIn("1500");
        $("#editprofileteacher").html("<i class='fa fa-spinner fa-spin'></i> Updating Profile....");
        
        $.ajax({
            url: 'classes/save_profile_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#update-profile").hide();
                $("#editprofileteacher").html("<i class='fa fa-save'></i> Save Profile");
                if(response[0]['status'] == "Success") {
                    toCapitalCase($("#name"));
                    $("#password").val("");
                    $("#confirm_password").val("");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    display_success_short(response[0]['message'], response[0]['status']);
                    setTimeout(function(){ window.location.href = 'dashboard.php'; }, 1550);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    /*-- Email --*/
    $("#sendemail").click(function(event) {
        event.preventDefault();
        $("#send-email").fadeIn("3000");
        $("#checkbox").prop("disabled", true);
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/send_email_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#send-email").hide();
                $("#checkbox").prop("disabled", false);
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_long(response[0]['message'], response[0]['status']);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#emailparents").click(function(event) {
        event.preventDefault();
        $("#send-email").fadeIn("3000");
        $("#checkbox").prop("disabled", true);
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/email_parents_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) { 
                $("#send-email").hide();
                $("#checkbox").prop("disabled", false);
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_long(response[0]['message'], response[0]['status']);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    /*-- SMS --*/
    $("#sendsms").click(function(event) {
        event.preventDefault();
        $("#send-sms").fadeIn("3000");
        $("#checkbox").prop("disabled", true);
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/send_sms_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#send-sms").hide();
                $("#checkbox").prop("disabled", false);
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_long(response[0]['message'], response[0]['status']);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

    $("#smsparents").click(function(event) {
        event.preventDefault();
        $("#send-sms").fadeIn("3000");
        $("#checkbox").prop("disabled", true);
        var formData = new FormData($('form')[0]);

        $.ajax({
            url: 'classes/sms_parents_xml.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response, status) {
                $("#send-sms").hide();
                $("#checkbox").prop("disabled", false);
                if(response[0]['status'] == "Success") {
                    $("form")[0].reset();
                    display_success_long(response[0]['message'], response[0]['status']);
                } else if(response[0]['status'] == "Warning") {
                    display_warning(response[0]['message'], response[0]['status']);
                } else {
                    display_error(response[0]['message'], response[0]['status']);
                }
            }, 
            error: function (request, xhr, thrownError) {
                alert(request.responseText + " " + xhr.status + " " + thrownError);
            }
        });
    });

});