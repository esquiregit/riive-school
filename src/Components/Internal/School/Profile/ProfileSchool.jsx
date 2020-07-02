import React, { useState } from 'react';
import md5 from 'md5';
import Card from '@material-ui/core/Card';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Loader from '../../../Extras/Loadrr';
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
import { FormikTextField, FormikSelectField } from 'formik-material-fields';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    name: Yup
        .string()
        .required('Please Fill In Name'),
    email: Yup
        .string()
        .required('Please Fill In Email Adress')
        .email('Invalid Email Adress Format'),
    location: Yup
        .string()
        .required('Please Fill In Location'),
    phone: Yup
        .string()
        .required('Please Fill In Phone Number')
        .min(10, 'Phone Number Must Contain Minimum Of 10 Digits'),
        // .test('non-number', 'Phone Number Must Contain Only Digits', function(value) {
        //     return isNaN(value);
        // }),
    region: Yup
        .string()
        .required('Please Select Region'),
    website: Yup
        .string()
        .required('Please Fill In Website'),
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

function ProfileSchool({ history, school }) {//console.log(school)
    const classes       = styles();
    const dispatch      = useDispatch();
    const displayImg    = school && getBaseURL()+school.image;
    const initialValues = {
        id               : school && school.id,
        name             : school && school.name,
        email            : school && school.email,
        phone            : school && school.phone,
        region           : school && school.region,
        username         : school && school.username,
        location         : school && school.location,
        website          : school && school.website,
        password         : '',
        access_level     : school && school.access_level,
        confirm_password : '',
    }

    const [error, setError]               = useState(false);
    const [loading, setLoading]           = useState(true);
    const [message, setMessage]           = useState('');
    const [success, setSuccess]           = useState('');
    const [regions, setRegions]           = useState([]);
    const [backdrop, setBackdrop]         = useState(false);
    const [comError, setComError]         = useState(false);
    const [imageObject, setImageObject]   = useState('');
    const [imagePreview, setImagePreview] = useState(displayImg);

    React.useEffect(() => {
        const abortController = new AbortController();
        const signal          = abortController.signal;
        
        if(school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_regions', { school_id: school.id, country_code: school.country_code }, { signal: signal })
                    .then(response => {
                        setRegions(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                        setMessage('Network Error. Server Unreachable');
                    });
            } else {
                getBack(history);
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [school, history, loading]);
    
    const displayImage = (event) => {
        setImageObject(event.target.files[0]);
        setImagePreview(URL.createObjectURL(event.target.files[0]));
    }
    const onSubmit     = (values) => {
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
        formData.append('phone',            values.phone);
        formData.append('region',           values.region);
        formData.append('username',         values.username);
        formData.append('location',         values.location);
        formData.append('website',          values.website);
        formData.append('password',         values.password && md5(values.password));
        formData.append('access_level',     values.access_level);
        formData.append('confirm_password', values.confirm_password && md5(values.confirm_password));
        
        Axios.post(getBaseURL() + 'update_profile_school', formData, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setSuccess(true);
                    dispatch(update(response.data[0].user[0]));
                    setMessage(response.data[0].message);
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
            {
                loading ? <Loader /> :
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
                                                width="90%"
                                                height="70%"
                                                alt="Profile"
                                                className="border-radius" />
                                        </div>
                                        <div>
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
                                                    id="location"
                                                    label="Location"
                                                    placeholder="Location"
                                                    name="location" />
                                            </Grid>
                                            <Grid item xs={12} md={6}>
                                                <FormikTextField
                                                    variant="outlined"
                                                    margin="normal"
                                                    fullWidth
                                                    id="phone"
                                                    label="Phone Number"
                                                    placeholder="Phone Number"
                                                    name="phone" />
                                            </Grid>
                                            <Grid item xs={12} md={6}>
                                                <FormikSelectField
                                                    options={regions}
                                                    variant="outlined"
                                                    margin="normal"
                                                    fullWidth
                                                    id="region"
                                                    label="Region"
                                                    name="region" />
                                            </Grid>
                                            <Grid item xs={12} md={6}>
                                                <FormikTextField
                                                    variant="outlined"
                                                    margin="normal"
                                                    fullWidth
                                                    id="website"
                                                    label="Website"
                                                    placeholder="Website"
                                                    name="website" />
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
            }
        </>
    )
}

export default ProfileSchool;
