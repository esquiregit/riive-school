import React, { useState } from 'react';
import md5 from 'md5';
import Card from '@material-ui/core/Card';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import styles from '../../../Extras/styles';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import PhotoCamera from '@material-ui/icons/PhotoCamera';
import CircularProgress from '@material-ui/core/CircularProgress';
import { update } from '../../../../Store/Actions/AuthActions';
import { getBack } from '../../../Extras/GoBack';
import { Tooltip } from '@material-ui/core';
import { getBaseURL } from '../../../Extras/server';
import { IconButton } from '@material-ui/core';
import { useDispatch } from 'react-redux';
import { Form, Formik } from 'formik';
import { FormikTextField } from 'formik-material-fields';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    name: Yup
        .string()
        .required('Please Fill In Name'),
    email: Yup
        .string()
        .required('Please Fill In Email Adress')
        .email('Invalid Email Adress Format'),
    contact: Yup
        .string()
        .required('Please Fill In Phone Number')
        .min(10, 'Phone Number Must Contain Minimum Of 10 Digits'),
        // .test('non-number', 'Phone Number Must Contain Only Digits', function(value) {
        //     return isNaN(value);
        // }),
    username: Yup
        .string()
        .required('Please Fill In Username'),
    password: Yup
        .string()
        .min(8, 'Password Must Contain At Least 8 Characters'),
    confirm_password: Yup
        .string()
        .test('password-mismatch', 'Passwords Don\'t Match', function(value) {
            return this.parent.password === value;
        }),
});

function ProfileTeacher({ history, teacher }) {//console.log(teacher)
    const classes       = styles();
    const dispatch      = useDispatch();
    const displayImg    = teacher && getBaseURL()+teacher.image;
    const initialValues = {
        id               : teacher && teacher.id,
        name             : teacher && teacher.name,
        email            : teacher && teacher.email,
        contact          : teacher && teacher.contact,
        username         : teacher && teacher.username,
        password         : '',
        school_id        : teacher && teacher.school_id,
        access_level     : teacher && teacher.access_level,
        confirm_password : '',
    }

    const [error, setError]               = useState(false);
    const [message, setMessage]           = useState('');
    const [success, setSuccess]           = useState('');
    const [backdrop, setBackdrop]         = useState(false);
    const [comError, setComError]         = useState(false);
    const [imageObject, setImageObject]   = useState('');
    const [imagePreview, setImagePreview] = useState(displayImg);

    React.useEffect(() => {        
        if(teacher) {
            if(teacher.access_level.toLowerCase() !== 'teacher') {
                getBack(history);
            }
        } else {
            history && history.push('/');
        }
    }, [teacher, history]);
    
    const displayImage = (event) => {
        setImageObject(event.target.files[0]);
        setImagePreview(URL.createObjectURL(event.target.files[0]));
    }
    const onSubmit     = (values, { resetForm }) => {
        setError(false);
        setSuccess(false);
        setBackdrop(true);
        setComError(false)
        const abortController = new AbortController();
        const signal          = abortController.signal;
        
        let formData = new FormData();
        formData.append('id',               values.id);
        formData.append('name',             values.name);
        formData.append('email',            values.email);
        formData.append('image',            imageObject);
        formData.append('contact',          values.contact);
        formData.append('username',         values.username);
        formData.append('password',         values.password && md5(values.password));
        formData.append('school_id',        values.school_id);
        formData.append('access_level',     values.access_level);
        formData.append('confirm_password', values.confirm_password && md5(values.confirm_password));
        
        Axios.post(getBaseURL() + 'update_profile_teacher', formData, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setSuccess(true);
                    dispatch(update(response.data[0].user[0]));
                    setMessage(response.data[0].message);
                    resetForm();
                } else {
                    setError(true);
                    setMessage(response.data[0].message);
                }
                setBackdrop(false);
            })
            .catch(error => {
                setMessage('Network Error. Server Unreachable....');
                setBackdrop(false);
                setComError(true);
            });

        return () => abortController.abort();
    }
    
    return (
        <>
            { error     && <Toastrr message={message} type="warning" />}
            { success   && <Toastrr message={message} type="success" />}
            { comError  && <Toastrr message={message} type="info"    />}
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Updating Profile....</span>
            </Backdrop>
            <Card className="pt-40 pr-40 pb-10 pl-40" variant="outlined">
                <Formik
                    initialValues={initialValues}
                    validationSchema={validationSchema}
                    onSubmit={values => onSubmit(values)} >
                    {({ isValid, dirty, resetForm }) => (
                        <Form encType="multipart/form-data">
                            <Grid container spacing={3}>
                                <Grid className={classes.fullHeight} item xs={12} md={3}>
                                    <div className={classes.fullHeightDiv}>
                                        <img
                                            src={imagePreview}
                                            width="300px"
                                            height="300px"
                                            alt="Profile"
                                            className="border-radius" />
                                    </div>
                                    <div className="mt-40">
                                        <Tooltip title="Upload Image">
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
                                    </div>
                                </Grid>
                                <Grid item xs={12} md={9}>
                                    <Grid container spacing={3}>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="name"
                                                label="Name"
                                                placeholder="Name"
                                                name="name" />
                                        </Grid>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="email"
                                                label="Email Address"
                                                placeholder="Email Address"
                                                name="email" />
                                        </Grid>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="contact"
                                                label="Phone Number"
                                                placeholder="Phone Number"
                                                name="contact" />
                                        </Grid>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="username"
                                                label="Username"
                                                placeholder="Username"
                                                name="username" />
                                        </Grid>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                type="password"
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="password"
                                                label="Password"
                                                placeholder="Password"
                                                name="password" />
                                        </Grid>
                                        <Grid item xs={12} md={6}>
                                            <FormikTextField
                                                type="password"
                                                variant="outlined"
                                                margin="normal"
                                                fullWidth
                                                id="confirm_password"
                                                label="Re-enter Password"
                                                placeholder="Re-enter Password"
                                                name="confirm_password" />
                                        </Grid>
                                    </Grid>
                                </Grid>
                            </Grid>
                            <Grid container spacing={3} style={{borderTop: '1px solid #ddd', marginTop: 40}}>
                                <Grid item xs={12} className="text-right">
                                    <Tooltip title="Reset Form">
                                        <Button
                                            onClick={resetForm}
                                            color="secondary">
                                            Reset
                                        </Button>
                                    </Tooltip>
                                    <Tooltip title="Save">
                                        <Button
                                            type="submit"
                                            disabled={!(isValid && dirty)}
                                            color="primary">
                                            Save
                                        </Button>
                                    </Tooltip>
                                </Grid>
                            </Grid>
                        </Form>
                    )}
                </Formik>
            </Card>
        </>
    )
}

export default ProfileTeacher;
