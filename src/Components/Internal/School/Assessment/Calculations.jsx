export const assessmentCalculation = (class_tests, assignments, interim_assessment, attendance_mark, exams_score) => {
    var total_score = '';
    var grade       = '';
    var remarks     = '';

    if(class_tests >= 0 && assignments >= 0 && interim_assessment >= 0 && attendance_mark >= 0 && exams_score >= 0) {
        total_score = Number(class_tests) + Number(assignments) + Number(interim_assessment) + Number(attendance_mark) + Number(exams_score);

        if(total_score >= 80) {
            grade   = 'A';
            remarks = 'Outstanding';
        } else if(total_score >= 75) {
            grade   = 'B+';
            remarks = 'Excellent';
        } else if(total_score >= 70) {
            grade   = 'B';
            remarks = 'Very Good';
        } else if(total_score >= 65) {
            grade   = 'C+';
            remarks = 'Good';
        } else if(total_score >= 60) {
            grade   = 'C';
            remarks = 'Above Average';
        } else if(total_score >= 55) {
            grade   = 'D+';
            remarks = 'Average';
        } else if(total_score >= 50) {
            grade   = 'D';
            remarks = 'Pass';
        } else if(total_score >= 45) {
            grade   = 'E+';
            remarks = 'Poor';
        } else if(total_score >= 40) {
            grade   = 'E';
            remarks = 'Very Poor';
        } else {
            grade   = 'F';
            remarks = 'Fail';
        }
        
        return total_score+'-'+grade+'-'+remarks;
    }
}

// export const validateValues = (class_tests, assignments, interim_assessment, attendance_mark, exams_score) => {
//     let status = true;

//     if(class_tests < 0 || class_tests > 10) {
//         status = false;
//         setClassTestsError('Class Tests Mark Must Be Between 0 And 10');
//     } else  {
//         status = true;
//         setClassTestsError('');
//     }
//     if(assignments < 0 || assignments > 5) {
//         status = false;
//         setAssignmentsError('Assignments Mark Must Be Between 0 And 5');
//     } else {
//         status = true;
//         setAssignmentsError('');
//     }
//     if(interim_assessment < 0 || interim_assessment > 10) {
//         status = false;
//         setInterimAssessmentError('Interim Assessment Marks Must Be Between 0 And 10');
//     } else  {
//         status = true;
//         setInterimAssessmentError('');
//     }
//     if(attendance_mark < 0 || attendance_mark > 5) {
//         status = false;
//         setAttendanceMarkError('attendance Marks Must Be Between 0 And 5');
//     } else  {
//         status = true;
//         setAttendanceMarkError('');
//     }
//     if(exams_score < 0 || exams_score > 70) {
//         status = false;
//         setExamsScoreError('Examination Marks Must Be Between 0 And 70');
//     } else {
//         status = true;
//         setExamsScoreError('');
//     }
    
//     return status;
// }
