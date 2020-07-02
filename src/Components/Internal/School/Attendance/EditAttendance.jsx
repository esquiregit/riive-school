import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import CircularProgress from '@material-ui/core/CircularProgress';
import { Tooltip } from '@material-ui/core';
import { getBaseURL } from '../../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { Form, Formik } from 'formik';
import { FormikSelectField, FormikTextField } from 'formik-material-fields';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../Extras/Dialogue';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    status: Yup
           .string()
           .required('Please Select Present Or Absent')
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function EditAttendance({ attendance, closeModal }) {//console.log(attendance)
    const options                 = [{ label: 'Present', value: 'Present'}, { label: 'Absent', value: 'Absent'}];
    const classes                 = useStyles();
    const initialValues           = {
        id: attendance.id,
        student: attendance.student,
        student_id: attendance.student_id,
        status: attendance.status,
        schoolCode: attendance.schoolCode
    }
    const [open, setOpen]         = useState(true);
    const [error, setError]       = useState(false);
    const [message, setMessage]   = useState('');
    const [backdrop, setBackdrop] = useState(false);
    const [comError, setComError] = useState(false);
    const handleClose             = () => { setOpen(false); closeModal(); }
    const onSubmit                = values => {
        const abortController     = new AbortController();
        const signal              = abortController.signal;
        
        setError(false);
        setBackdrop(true);
        setComError(false);
        Axios.post(getBaseURL()+'update_attendance', values, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setOpen(false);
                    setError(false);
                    closeModal('reload', response.data[0].message, null);
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

        return () => abortController.abort();
    }

    return (
        <>
            { error     && <Toastrr message={message} type="warning" />}
            { comError  && <Toastrr message={message} type="info" />}
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Updating Attendance....</span>
            </Backdrop>
            <Dialog
                TransitionComponent={Transition}
                disableBackdropClick={true}
                disableEscapeKeyDown={true}
                scroll='paper'
                fullWidth={true}
                maxWidth='sm'
                onClose={handleClose}
                aria-labelledby="customized-dialog-title"
                open={open}>
                <Formik
                    initialValues={initialValues}
                    validationSchema={validationSchema}
                    onSubmit={onSubmit} >
                    {({ isValid, dirty, resetForm, values }) => (
                        <Form>
                            <DialogTitle id="customized-dialog-title" onClose={handleClose}>
                                Update Attendance
                            </DialogTitle>
                            <DialogContent dividers>
                                <Grid container spacing={3}>
                                    <Grid item xs={12}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="student"
                                            label="Student"
                                            name="student"
                                            disabled />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <FormikSelectField
                                            options={options}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="status"
                                            label="Status"
                                            name="status" />
                                    </Grid>
                                </Grid>
                            </DialogContent>
                            <DialogActions>
                                <Tooltip title="Reset Form">
                                    <Button
                                        onClick={resetForm}
                                        color="secondary">
                                        Reset
                                    </Button>
                                </Tooltip>
                                {/* <Tooltip title="Update Attendance Details"> */}
                                    <Button
                                        type="submit"
                                        disabled={!(isValid && dirty)}
                                        color="primary">
                                        Update Attendance
                                    </Button>
                                {/* </Tooltip> */}
                            </DialogActions>
                        </Form>
                    )}
                </Formik>
            </Dialog>
        </>
    );
}

export default EditAttendance;
