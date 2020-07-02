import React, { useEffect, useState } from 'react';
import md5 from 'md5';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Toastrr from '../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Typography from '@material-ui/core/Typography';
import CircularProgress from '@material-ui/core/CircularProgress';
import { Link } from 'react-router-dom';
import { logIn } from '../../../Store/Actions/AuthActions';
import { makeStyles } from '@material-ui/core/styles';
import { getBaseURL } from '../../Extras/server';
import { useDispatch } from 'react-redux';
import { Form, Formik } from 'formik';
import { FormikTextField } from 'formik-material-fields';
import * as Yup from 'yup';
import './Login.css';

const initialValues = {
    username : '',
    password : ''
}
const validationSchema = Yup.object().shape({
    username : Yup
             .string()
             .required('Please Fill In Your Username or Email Address'),
            //  .email('Invalid Email Address Format'),
    password : Yup
             .string()
             .required('Please Fill In Your Password')
             .min(8, 'Password Must Be At Least 8 Characters Long')
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

const Login = ({ history }) => {
    // const user                        = useSelector(state => state.authReducer.user);
    // const isLoggedIn                  = useSelector(state => state.authReducer.isLoggedIn);
    const [message, setMessage]       = useState('');
    const [logSuccess, setLogSuccess] = useState(false);
    // if(isLoggedIn) {
    //     setMessage('You\'re Already Logged In....');
    //     setLogSuccess(true);
    //     history.push('/dashboard/');
    // }
    const classes                 = useStyles();
    const [open, setOpen]         = useState(false);
    const [comError, setComError] = useState(false);
    const [logError, setLogError] = useState(false);
    const dispatch                = useDispatch();
    useEffect(() => {
        document.title = 'RiiVe | Login';
    }, []);
    // }, [user, history]);
    const onSubmit = values => {
        setOpen(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;

        setComError(false);
        setLogError(false);
        setLogSuccess(false);

        Axios.post(getBaseURL()+'login', { username: values.username, password: md5(values.password) }, { signal: signal })
            .then(response => {
                if(response.data[0].status[0].status.toLowerCase() === 'success') {
                    setLogSuccess(true);
                    setLogError(false);
                    setMessage(response.data[0].status[0].message);
                    dispatch(logIn(response.data[0].user[0]));
                    setTimeout(() => history.push('/dashboard/'), 2050);
                } else {
                    setLogError(true);
                    setLogSuccess(false);
                    setMessage(response.data[0].status[0].message);
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
            { logError   && <Toastrr message={message} type="warning" /> }
            { comError   && <Toastrr message={message} type="info" /> }
            { logSuccess && <Toastrr message={message} type="success" /> }
            <Backdrop className={classes.backdrop} open={open}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Authenticating....</span>
            </Backdrop>

            <Formik
                initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={onSubmit}
            >
                {(props) => (   
                    <div className='login-div'>
                        <Form className="login-form">
                            <Typography className="mb-30" component="h1" variant="h4">
                                Log in
                            </Typography>
                            <FormikTextField
                                variant="outlined"
                                margin="normal"
                                fullWidth
                                id="username"
                                label="Username/Email Address"
                                placeholder="Username/Email Address"
                                name="username"
                                autoComplete="email address"
                            />
                            <FormikTextField
                                variant="outlined"
                                margin="normal"
                                fullWidth
                                id="password"
                                label="Password"
                                placeholder="Password"
                                name="password"
                                type="password"
                            />
                            <Button
                                size="large"
                                type="submit"
                                fullWidth
                                variant="contained"
                                color="primary"
                                className='text-capitalise mt-20'
                                disableElevation>
                                Log In
                            </Button>
                            <Link to="/password-recovery/" variant="body2" className="mt-20">
                                Forgot password?
                            </Link>
                        </Form>
                    </div>
                )}
            </Formik>
        </>
    );
}

export default Login;
