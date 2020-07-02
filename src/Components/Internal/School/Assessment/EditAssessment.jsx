import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import TextField from '@material-ui/core/TextField';
import CircularProgress from '@material-ui/core/CircularProgress';
import FormErrorMessage from '../../../Extras/FormErrorMessage/FormErrorMessage';
import DialogContentText from '@material-ui/core/DialogContentText';
import { Tooltip } from '@material-ui/core';
import { getBaseURL } from '../../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { assessmentCalculation } from './Calculations';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../Extras/Dialogue';

const useStyles = makeStyles((theme) => ({
    backdrop: {
        zIndex: theme.zIndex.drawer + 1,
        color: '#fff',
    },
}));

const EditAssessement = ({ school_id, teacher_id, assessment, closeModal }) => {
    const classes                   = useStyles();
    const [values, setValues]       = useState([
        { a_id              : assessment.a_id },
        { term              : assessment.term },
        { class             : assessment.class },
        { grade             : assessment.grade },
        { student           : assessment.student },
        { subject           : assessment.subject },
        { remarks           : assessment.remarks },
        { exams_score       : assessment.exams_score },
        { total_score       : assessment.total_score },
        { class_tests       : assessment.class_tests },
        { assignments       : assessment.assignments },
        { academic_year     : assessment.academic_year },
        { interim_assessment: assessment.interim_assessment },
        { attendance_mark   : assessment.attendance_mark },
        { school_id         : school_id },
        { teacher_id        : teacher_id },
    ]);

    const [open, setOpen]                   = useState(true);
    const [error, setError]                 = useState(false);
    const [message, setMessage]             = useState('');
    const [backdrop, setBackdrop]           = useState(false);
    const [comError, setComError]           = useState(false);
    const [formValid, setFormValid]         = useState(false);
    const [isConfirmOpen, setIsConfirmOpen] = useState(false);

    const [classTestsError, setClassTestsError]               = useState('');
    const [examsScoreError, setExamsScoreError]               = useState('');
    const [assignmentsError, setAssignmentsError]             = useState('');
    const [attendanceMarkError, setAttendanceMarkError]       = useState('');
    const [interimAssessmentError, setInterimAssessmentError] = useState('');

    const handleClose     = () => { setOpen(false); closeModal(); }
    const handleChange    = (event) => {
        if(event) {
            const name  = event.target.name;
            const value = event.target.value;
            let newArr  = [...values];
            if(name === 'class_tests') {
                newArr[9]['class_tests'] = value;
            } else if(event.target.name === 'assignments') {
                newArr[10]['assignments'] = value;
            } else if(event.target.name === 'interim_assessment') {
                newArr[12]['interim_assessment'] = value;
            } else if(event.target.name === 'attendance_mark') {
                newArr[13]['attendance_mark'] = value;
            } else if(event.target.name === 'exams_score') {
                newArr[7]['exams_score'] = value;
            }

            setValues(newArr);
            calculateChange();
            setFormValid(validateValues());
        }
    }
    const calculateChange = () => {
        const retVal      = assessmentCalculation(values[9].class_tests, values[10].assignments, values[12].interim_assessment, values[13].attendance_mark, values[7].exams_score);
        let newArr        = [...values];
        let newGrade      = 0;
        let newRemarks    = 0;
        let newTotalScore = 0;
        if(retVal !== undefined) {
            const vals    = retVal.split('-');
            newGrade      = vals[1];
            newRemarks    = vals[2];
            newTotalScore = vals[0];
        }

        newArr[3]['grade']       = newGrade;
        newArr[6]['remarks']     = newRemarks;
        newArr[8]['total_score'] = newTotalScore;
        setValues(newArr);
    }
    const validateValues  = () => {
        let status               = true;
        const class_tests        = values[9].class_tests;
        const assignments        = values[10].assignments;
        const interim_assessment = values[12].interim_assessment;
        const attendance_mark    = values[13].attendance_mark;
        const exams_score        = values[7].exams_score;

        if(!class_tests) {
            status = false;
            setClassTestsError('Please Fill In Class Tests Mark');
        } else if(class_tests < 0 || class_tests > 10) {
            status = false;
            setClassTestsError('Class Tests Mark Must Be Between 0 And 10');
        } else  {
            setClassTestsError('');
        }
        if(!assignments) {
            status = false;
            setAssignmentsError('Please Fill In Assignments Mark');
        } else if(assignments < 0 || assignments > 5) {
            status = false;
            setAssignmentsError('Assignments Mark Must Be Between 0 And 5');
        } else {
            setAssignmentsError('');
        }
        if(!interim_assessment) {
            status = false;
            setInterimAssessmentError('Please Fill In Interim Assessment Marks');
        } else if(interim_assessment < 0 || interim_assessment > 10) {
            status = false;
            setInterimAssessmentError('Interim Assessment Marks Must Be Between 0 And 10');
        } else  {
            setInterimAssessmentError('');
        }
        if(!attendance_mark) {
            status = false;
            setAttendanceMarkError('Please Fill In Attendance Marks');
        } else if(attendance_mark < 0 || attendance_mark > 5) {
            status = false;
            setAttendanceMarkError('Attendance Marks Must Be Between 0 And 5');
        } else  {
            setAttendanceMarkError('');
        }
        if(!exams_score) {
            status = false;
            setExamsScoreError('Please Fill In Examination Marks');
        } else if(exams_score < 0 || exams_score > 70) {
            status = false;
            setExamsScoreError('Examination Marks Must Be Between 0 And 70');
        } else {
            setExamsScoreError('');
        }

        return status;
    }
    React.useEffect(()    => {
        handleChange();
    }, [values]);
    const confirm         = (event) => {
        event.preventDefault();
        setIsConfirmOpen(true);
    };
    const submit          = (action) => {
        setIsConfirmOpen(false);
        
        if(action.toLowerCase() === 'yes') {
            const data = {
                a_id               : values[0].a_id,
                term               : values[1].term,
                grade              : values[3].grade,
                remarks            : values[6].remarks,
                student            : values[4].student,
                school_id          : values[14].school_id,
                teacher_id         : values[15].teacher_id,
                assignments        : values[10].assignments,
                class_tests        : values[9].class_tests,
                exams_score        : values[7].exams_score,
                total_score        : values[8].total_score,
                academic_year      : values[11].academic_year,
                attendance_mark    : values[13].attendance_mark,
                interim_assessment : values[12].interim_assessment,
            }
            setError(false);
            setBackdrop(true);
            setComError(false);
            Axios.post(getBaseURL()+'edit_assessment', data)
                .then(response => {
                    if(response.data[0].status.toLowerCase() === 'success') {
                        setOpen(false);
                        setError(false);
                        closeModal('reload', response.data[0].message);
                    } else {
                        setError(true);
                        setMessage(response.data[0].message);
                    }
                    setBackdrop(false);
                })
                .catch(error => {
                    setComError(true);
                    setBackdrop(false);
                    setMessage('Network Error. Server Unreachable....');
                });
        }
    };

    return (
        <>
            { error        && <Toastrr message={message} type="warning" />}
            { comError     && <Toastrr message={message} type="info" />}
            { isConfirmOpen &&
                <div>
                    <Dialog
                        open={isConfirmOpen}
                        keepMounted
                        onClose={handleClose}
                        disableBackdropClick={true}
                        disableEscapeKeyDown={true}
                        TransitionComponent={Transition}
                        aria-labelledby="alert-dialog-slide-title"
                        aria-describedby="alert-dialog-slide-description" >
                        <DialogTitle id="alert-dialog-slide-title">Confirm Action</DialogTitle>
                        <DialogContent>
                            <DialogContentText id="alert-dialog-slide-description">
                                Are You Sure You Want To Update Assessement?
                            </DialogContentText>
                        </DialogContent>
                        <DialogActions>
                            <Button onClick={() => submit('No')} color="primary">
                                No
                            </Button>
                            <Button onClick={() => submit('Yes')} color="secondary">
                                Yes
                            </Button>
                        </DialogActions>
                    </Dialog>
                </div>
            }
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Updating Assessement....</span>
            </Backdrop>
            <Dialog
                TransitionComponent={Transition}
                disableBackdropClick={true}
                disableEscapeKeyDown={true}
                scroll='paper'
                fullWidth={true}
                maxWidth='md'
                onClose={handleClose}
                aria-labelledby="customized-dialog-title"
                open={open}>
                {/* <form onSubmit={onSubmit}> */}
                <form onSubmit={confirm}>
                    <DialogTitle id="customized-dialog-title" onClose={handleClose}>
                        Update Assessement
                    </DialogTitle>
                    <DialogContent dividers>
                        <Grid container spacing={3}>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="student"
                                    label="Student"
                                    placeholder="Student"
                                    name="student"
                                    value={values[4].student} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="class"
                                    label="Class"
                                    placeholder="Class"
                                    name="class"
                                    value={values[2].class} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="term"
                                    label="Term"
                                    placeholder="Term"
                                    name="term"
                                    value={values[1].term} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="academic_year"
                                    label="Academic Year"
                                    placeholder="Academic Year"
                                    name="academic_year"
                                    value={values[11].academic_year} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="subject"
                                    label="Subject"
                                    placeholder="Subject"
                                    name="subject"
                                    value={values[5].subject} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    onChange={e => handleChange(e)}
                                    type="number"
                                    InputProps={{ inputProps: { min: 0, step: 0.5, max: 10 } }}
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="class_tests"
                                    label="Class Test - Maximum: 10"
                                    placeholder="Class Test - Maximum: 10"
                                    name="class_tests"
                                    value={values[9].class_tests} />
                                {classTestsError && <FormErrorMessage message={classTestsError} />}
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    onChange={e => handleChange(e)}
                                    type="number"
                                    InputProps={{ inputProps: { min: 0, step: 0.5, max: 5 } }}
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="assignments"
                                    label="Assignments - Maximum: 5"
                                    placeholder="Assignments - Maximum: 5"
                                    name="assignments"
                                    value={values[10].assignments} />
                                {assignmentsError && <FormErrorMessage message={assignmentsError} />}
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    onChange={e => handleChange(e)}
                                    type="number"
                                    InputProps={{ inputProps: { min: 0, step: 0.5, max: 10 } }}
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="interim_assessment"
                                    label="Interim Assessment - Maximum: 10"
                                    placeholder="Interim Assessment - Maximum: 10"
                                    name="interim_assessment"
                                    value={values[12].interim_assessment} />
                                {interimAssessmentError && <FormErrorMessage message={interimAssessmentError} />}
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    onChange={e => handleChange(e)}
                                    type="number"
                                    InputProps={{ inputProps: { min: 0, step: 0.5, max: 5 } }}
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="attendance_mark"
                                    label="Attendance - Maximum: 5"
                                    placeholder="Attendance - Maximum: 5"
                                    name="attendance_mark"
                                    value={values[13].attendance_mark} />
                                {attendanceMarkError && <FormErrorMessage message={attendanceMarkError} />}
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    onChange={e => handleChange(e)}
                                    type="number"
                                    InputProps={{ inputProps: { min: 0, step: 0.5, max: 70 } }}
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="exams_score"
                                    label="Exams - Maximum: 70"
                                    placeholder="Exams - Maximum: 70"
                                    name="exams_score"
                                    value={values[7].exams_score} />
                                {examsScoreError && <FormErrorMessage message={examsScoreError} />}
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="total_score"
                                    label="Total Score"
                                    placeholder="Total Score"
                                    name="total_score"
                                    value={values[8].total_score} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="grade"
                                    label="Grade"
                                    placeholder="Grade"
                                    name="grade"
                                    value={values[3].grade} />
                            </Grid>
                            <Grid item xs={12} sm={6}>
                                <TextField
                                    disabled
                                    variant="outlined"
                                    margin="normal"
                                    fullWidth
                                    id="remarks"
                                    label="Remarks"
                                    placeholder="Remarks"
                                    name="remarks"
                                    value={values[6].remarks} />
                            </Grid>
                        </Grid>
                    </DialogContent>
                    <DialogActions>
                        <Tooltip title="Reset Form">
                            <Button
                                color="secondary">
                                Reset
                            </Button>
                        </Tooltip>
                        {/* <Tooltip title="Update Assessement Details"> */}
                            <Button
                                disabled={!formValid}
                                type="submit"
                                color="primary">
                                Update Assessement
                            </Button>
                        {/* </Tooltip> */}
                    </DialogActions>
                </form>
            </Dialog>
        </>
    );
}

export default EditAssessement;
