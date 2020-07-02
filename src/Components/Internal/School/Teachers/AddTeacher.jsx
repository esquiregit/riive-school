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
import { useSelector } from 'react-redux';
import { FormikTextField } from 'formik-material-fields';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../Extras/Dialogue';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    firstname: Yup
        .string()
        .required('Please Fill In Teacher\'s First Name'),
    othernames: Yup
        .string(),
    lastname: Yup
        .string()
        .required('Please Fill In Teacher\'s Last Name'),
    email: Yup
        .string()
        .required('Please Fill In Teacher\'s Email Address')
        .email('Invalid Emaill Address Format Entered'),
    contact: Yup
        .string()
        .required('Please Select Teacher\'s Phone Number')
        .min(10, 'Phone Number MUST Contain 10 Digits')
        .max(10, 'Phone Number MUST Contain 10 Digits'),
        // .test('non-numeric', 'Phone Number Must Contain ONLY Digits', function(value) {
        //     return Number.isNaN(value);
        // }),
    username: Yup
        .string()
        .required('Please Select Teacher\'s Username')
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function AddTeacher({ closeModal, closeExpandable }) {
    const classes       = useStyles();
    const user          = useSelector(state => state.authReducer.user);
    const initialValues = {
        email:      '',
        contact:    '',
        lastname:   '',
        username:   '',
        firstname:  '',
        country_id: user.country_code,
        othernames: '',
        schoolCode: user.id,
    }
    const [open, setOpen]         = useState(true);
    const [error, setError]       = useState(false);
    const [message, setMessage]   = useState('');
    const [success, setSuccess]   = useState(false);
    const [backdrop, setBackdrop] = useState(false);
    const [comError, setComError] = useState(false);

    const handleClose = () => { setOpen(false); closeModal(); }
    const onSubmit    = values => {
        const abortController = new AbortController();
        const signal          = abortController.signal;

        setBackdrop(true);
        Axios.post(getBaseURL()+'add_teacher', values, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setOpen(false);
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    closeExpandable();
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
            { success   && <Toastrr message={message} type="success" />}
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Adding Teacher....</span>
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
                <Formik
                    initialValues={initialValues}
                    validationSchema={validationSchema}
                    onSubmit={onSubmit} >
                    {({ isValid, dirty, resetForm, values }) => (
                        <Form>
                            <DialogTitle id="customized-dialog-title" onClose={handleClose}>
                                Add Teacher
                            </DialogTitle>
                            <DialogContent dividers>
                                <Grid container spacing={3}>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="firstname"
                                            label="First Name"
                                            placeholder="First Name"
                                            name="firstname" />
                                    </Grid>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="othernames"
                                            label="Other Name"
                                            placeholder="Other Name"
                                            name="othernames" />
                                    </Grid>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="lastname"
                                            label="Last Name"
                                            placeholder="Last Name"
                                            name="lastname" />
                                    </Grid>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="email"
                                            label="Email Address"
                                            placeholder="Email Address"
                                            name="email" />
                                    </Grid>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="contact"
                                            label="Phone Number"
                                            placeholder="Phone Number"
                                            name="contact" />
                                    </Grid>
                                    <Grid item xs={12} sm={6}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="username"
                                            label="Username"
                                            placeholder="Username"
                                            name="username" />
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
                                {/* <Tooltip title="Add Teacher Details"> */}
                                    <Button
                                        type="submit"
                                        disabled={!(isValid && dirty)}
                                        color="primary">
                                        Add Teacher
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

export default AddTeacher;
