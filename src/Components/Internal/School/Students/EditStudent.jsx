import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import PhotoCamera from '@material-ui/icons/PhotoCamera';
import CircularProgress from '@material-ui/core/CircularProgress';
import { Tooltip } from '@material-ui/core';
import { getBaseURL } from '../../../Extras/server';
import { getMaxDate } from '../../../Extras/Date';
import { makeStyles } from '@material-ui/core/styles';
import { IconButton } from '@material-ui/core';
import { Form, Formik } from 'formik';
import { useSelector } from 'react-redux';
import { classesOptions, genderOptions } from '../../../Extras/ClassesOptions';
import { FormikTextField, FormikSelectField } from 'formik-material-fields';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../Extras/Dialogue';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    iamge: Yup
        .string(),
    firstname: Yup
        .string()
        .required('Please Fill In Student\'s First Name'),
    othernames: Yup
        .string(),
    lastname: Yup
        .string()
        .required('Please Fill In Student\'s Last Name'),
    gender: Yup
        .string()
        .required('Please Select Student\'s Gender'),
    class: Yup
        .string()
        .required('Please Select Student\'s Class'),
    dob: Yup
        .string()
        .required('Please Fill In Student\'s Date of Birth'),
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function EditStudent({ student, closeModal }) {
    const maxDate       = getMaxDate();
    const classes       = useStyles();
    const user          = useSelector(state => state.authReducer.user);
    const classOptions  = classesOptions();
    const genderOption  = genderOptions();
    const initialValues = {
        dob          : student.dob,
        name         : user.name,
        class        : student.class,
        gender       : student.gender,
        lastname     : student.lastname,
        firstname    : student.firstname,
        school_id    : user && user.access_level.toLowerCase() === 'school' ? user.id : user.school_id,
        studentid    : student.studentid,
        othernames   : student.othernames,
        teacher_id   : user && user.access_level.toLowerCase() === 'teacher' ? user.id : '',
        access_level : user.access_level,
    }
    const [open, setOpen]                 = useState(true);
    const [error, setError]               = useState(false);
    const [message, setMessage]           = useState('');
    const [backdrop, setBackdrop]         = useState(false);
    const [comError, setComError]         = useState(false);
    const [imageObject, setImageObject]   = useState('');
    const [imagePreview, setImagePreview] = useState(getBaseURL()+student.imagePath+'/'+student.image);

    const displayImage = (event) => {
        setImageObject(event.target.files[0]);
        setImagePreview(URL.createObjectURL(event.target.files[0]));
    }
    const handleClose  = () => { setOpen(false); closeModal(); }
    const onSubmit     = values => {
        const abortController = new AbortController();
        const signal          = abortController.signal;

        setError(false);
        setBackdrop(true);
        setComError(false);

        let formData = new FormData();
        formData.append('dob', values.dob);
        formData.append('name', values.name);
        formData.append('class', values.class);
        formData.append('image', imageObject);
        formData.append('gender', values.gender);
        formData.append('lastname', values.lastname);
        formData.append('firstname', values.firstname);
        formData.append('school_id', values.school_id);
        formData.append('studentid', values.studentid);
        formData.append('othernames', values.othernames);
        formData.append('teacher_id',   values.teacher_id);
        formData.append('access_level', values.access_level);

        Axios.post(getBaseURL()+'edit_student', formData, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setOpen(false);
                    setError(false);
                    setMessage(response.data[0].message);
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

        return () => abortController.abort();
    }

    return (
        <>
            { error     && <Toastrr message={message} type="warning" />}
            { comError  && <Toastrr message={message} type="info" />}
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Saving Student Details....</span>
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
                        <Form encType="multipart/form-data">
                            <DialogTitle id="customized-dialog-title" onClose={handleClose}>
                                Update Student
                            </DialogTitle>
                            <DialogContent dividers>
                                <Grid container spacing={3}>
                                    <Grid item xs={12} sm={5}>
                                        <div className="image-div">
                                            <img src={imagePreview} width="300px" height="100px" alt="" />
                                        </div>
                                    </Grid>
                                    <Grid item xs={12} sm={7}>
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="firstname"
                                            label="First Name"
                                            placeholder="First Name"
                                            name="firstname" />
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="othernames"
                                            label="Other Name"
                                            placeholder="Other Name"
                                            name="othernames" />
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="lastname"
                                            label="Last Name"
                                            placeholder="Last Name"
                                            name="lastname" />
                                        <FormikSelectField
                                            disabled={user && user.class ? true : false}
                                            options={classOptions}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="class"
                                            label="Class"
                                            name="class" />
                                        <FormikSelectField
                                            options={genderOption}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="gender"
                                            label="Gender"
                                            name="gender" />
                                        <FormikTextField
                                            InputProps={{ inputProps: { min: 0, max: maxDate } }}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="dob"
                                            label="Date of Birth"
                                            name="dob"
                                            type="date" />
                                        <Tooltip title="Upload Student's Image">
                                            <label htmlFor="image">
                                                <IconButton
                                                    color="primary"
                                                    aria-label="upload picture"
                                                    component="span">
                                                    <PhotoCamera />
                                                </IconButton>
                                            </label>
                                        </Tooltip>
                                        <FormikTextField
                                            onChange={displayImage}
                                            className="hidden"
                                            id="image"
                                            name="image"
                                            type="file" />
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
                                {/* <Tooltip title="Update Student Details"> */}
                                    <Button
                                        type="submit"
                                        disabled={!(isValid && dirty)}
                                        color="primary">
                                        Update Details
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

export default EditStudent;
