import React, { useEffect, useState } from 'react';
import md5 from 'md5';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Toastrr from '../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Typography from '@material-ui/core/Typography';
import CircularProgress from '@material-ui/core/CircularProgress';
import { getBaseURL } from '../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { Form, Formik } from 'formik';
import { FormikTextField } from 'formik-material-fields';
import * as Yup from 'yup';
import '../Recovery/Recovery.css';

const initialValues  = {
    password         : '',
    confirm_password : '',
}
const validationSchema = Yup.object().shape({
    password: Yup
        .string()
        .required('Please Enter New Password')
        .min(8, 'Password Must Contain At Least 8 Characters'),
    confirm_password: Yup
        .string()
        .required('Please Re-enter New Password')
        .test('password-mismatch', 'Passwords Don\'t Match', function(value) {
            return this.parent.password === value;
        }),
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

const PasswordChange = ({ match, history }) => {
    const id      = match.params.id;
    const sid     = match.params.sid;
    const code    = match.params.code;
    const type    = match.params.type;
    const classes = useStyles();

    const [open, setOpen]         = useState(false);
    const [error, setError]       = useState(false);
    const [message, setMessage]   = useState(false);
    const [success, setSuccess]   = useState(false);
    const [comError, setComError] = useState(false);

    useEffect(() => {
        document.title = 'RiiVe | Password Change';
    }, []);

    const onSubmit = (values, { resetForm }) => {
        setOpen(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;

        const data = {
            id               : id,
            sid              : sid,
            code             : code,
            type             : type,
            password         : md5(values.password),
            confirm_password : md5(values.confirm_password),
        };
        
        setError(false);
        setSuccess(false);
        setComError(false);

        Axios.post(getBaseURL()+'password_change', data, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    resetForm();
                    setTimeout(() => history.push('/'), 3000);
                } else {
                    setError(true);
                    setMessage(response.data[0].message);
                }
                setOpen(false);
            })
            .catch(error => {
                setOpen(false);
                setComError(true);
                setMessage('Network Error. Server Unreachable....');
            });

        return () => abortController.abort();
    }

    return (
        <>
            { error    && <Toastrr message={message} type="warning" /> }
            { success  && <Toastrr message={message} type="success" /> }
            { comError && <Toastrr message={message} type="info"    /> }
            <Backdrop className={classes.backdrop} open={open}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Changing Password....</span>
            </Backdrop>

            <Formik
                initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={onSubmit}>
                {() => (  
                    <div className='recovery-div'>
                        <Form className="recovery-form">
                            <Typography className="mb-30" component="h1" variant="h4">
                                Password Change
                            </Typography>
                            <FormikTextField
                                variant="outlined"
                                margin="normal"
                                fullWidth
                                id="password"
                                label="New Password"
                                placeholder="New Password"
                                name="password"
                                type="password" />
                            <FormikTextField
                                variant="outlined"
                                margin="normal"
                                fullWidth
                                id="confirm_password"
                                label="Re-enter Password"
                                placeholder="Re-enter Password"
                                name="confirm_password"
                                type="password" />
                            <Button
                                size="large"
                                type="submit"
                                fullWidth
                                variant="contained"
                                color="primary"
                                className='text-capitalise mt-20'
                                disableElevation>
                                Change Password
                            </Button>
                        </Form>
                    </div>
                )}
            </Formik>
        </>
    );
}

export default PasswordChange;
