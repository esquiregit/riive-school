import React, { useEffect, useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Toastrr from '../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Typography from '@material-ui/core/Typography';
import CircularProgress from '@material-ui/core/CircularProgress';
import { Link } from 'react-router-dom';
import { getBaseURL } from '../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { Form, Formik } from 'formik';
import { FormikTextField } from 'formik-material-fields';
import * as Yup from 'yup';
import './Recovery.css';

const initialValues = { email_address   : '' }
const validationSchema = Yup.object().shape({
    email_address      : Yup
                       .string()
                       .required('Please Fill In Your Email Address')
                       .email('Invalid Email Address Format')
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

const Recovery    = ({ history }) => {
    const classes = useStyles();

    const [open, setOpen]         = useState(false);
    const [error, setError]       = useState(false);
    const [message, setMessage]   = useState(false);
    const [success, setSuccess]   = useState(false);
    const [warning, setWarning]   = useState(false);
    const [comError, setComError] = useState(false);
    useEffect(() => {
        document.title = 'RiiVe | Password Recovery';
    }, []);

    const onSubmit = (values, { resetForm }) => {
        setOpen(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;
        
        setError(false);
        setSuccess(false);
        setWarning(false);
        setComError(false);

        Axios.post(getBaseURL()+'password_recovery', { email_address: values.email_address }, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    resetForm();
                    // setTimeout(() => history.push('/'), 2050);
                } else if(response.data[0].status.toLowerCase() === 'warning') {
                    setWarning(true);
                    setMessage(response.data[0].message);
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
            { error    && <Toastrr message={message} type="error"   /> }
            { warning  && <Toastrr message={message} type="warning" /> }
            { success  && <Toastrr message={message} type="success" /> }
            { comError && <Toastrr message={message} type="info"    /> }
            <Backdrop className={classes.backdrop} open={open}>
                <CircularProgress color="inherit" />
                <span className='ml-15'>Sending Reset Link....</span>
            </Backdrop>

            <Formik
                initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={onSubmit}>
                {() => (   
                    <div className='recovery-div'>
                        <Form className="recovery-form">
                            <Typography className="mb-30" component="h1" variant="h4">
                                Password Recovery
                            </Typography>
                            <FormikTextField
                                variant="outlined"
                                margin="normal"
                                fullWidth
                                id="email_address"
                                label="Email Address"
                                placeholder="Email Address"
                                name="email_address"
                                autoComplete="email_address"
                            />
                            <Button
                                size="large"
                                type="submit"
                                fullWidth
                                variant="contained"
                                color="primary"
                                className='text-capitalise mt-20'
                                disableElevation>
                                Send Reset Link
                            </Button>
                            <Grid container>
                                <Grid item xs className="mt-20 text-centre">
                                    <Link to="/" variant="body2">
                                        Log In Instead
                                    </Link>
                                </Grid>
                            </Grid>
                        </Form>
                    </div>
                )}
            </Formik>
        </>
    );
}

export default Recovery;
