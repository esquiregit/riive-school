import React, { useEffect, useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import LoaderInnerRow from '../../../Extras/LoadrrInnerRow';
import CircularProgress from '@material-ui/core/CircularProgress';
import { Tooltip } from '@material-ui/core';
import { getBaseURL } from '../../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { Form, Formik } from 'formik';
import { useSelector } from 'react-redux';
import { classesOptions } from '../../../Extras/ClassesOptions';
import { FormikSelectField } from 'formik-material-fields';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../Extras/Dialogue';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    teacher_id: Yup
               .string()
               .required('Please Select Teacher To Assign'),
    class:     Yup
               .string()
               .required('Please Select Class To Assign')
});
const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function AssignTeacherClass({ closeModal }) {
    const school = useSelector(state => state.authReducer.user);
    useEffect(() => {
        setLoading(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;

        Axios.post(getBaseURL() + 'get_non_assigned_teachers', { schoolCode: school.id }, { signal: signal })
            .then(response => {
                setTeachers(response.data);
                setLoading(false);
            })
            .catch(error => {
                setComError(true);
                setLoading(false);
            });

        return () => abortController.abort();
    }, [school])
    const options       = classesOptions();
    const classes       = useStyles();
    const initialValues = {
        teacher_id: '',
        schoolCode: school.id,
        class: '',
    }
    const [open, setOpen]         = useState(true);
    const [error, setError]       = useState(false);
    const [loading, setLoading]   = useState(false);
    const [message, setMessage]   = useState('');
    const [success, setSuccess]   = useState(false);
    const [teachers, setTeachers] = useState([]);
    const [backdrop, setBackdrop] = useState(false);
    const [comError, setComError] = useState(false);
    const handleClose             = () => { setOpen(false); closeModal(); }
    const onSubmit                = values => {
        console.log(values)
        setError(false);
        setBackdrop(true);
        setSuccess(false);
        setComError(false);
        Axios.post(getBaseURL()+'assign_teacher', values)
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setOpen(false);
                    setError(false);
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    setTimeout(() => closeModal('reload', response.data[0].message), 2000);
                } else {
                    setError(true);
                    setSuccess(false);
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

    return (
        <>
            { error     && <Toastrr message={message} type="warning" />}
            { comError  && <Toastrr message={message} type="info" />}
            { success   && <Toastrr message={message} type="success" />}
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Assigning Teacher To Class....</span>
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
                {
                    loading ? <LoaderInnerRow /> :
                        <Formik
                            initialValues={initialValues}
                            validationSchema={validationSchema}
                            onSubmit={onSubmit} >
                            {({ isValid, dirty, resetForm, values }) => (
                                <Form>
                                    <DialogTitle id="customized-dialog-title" onClose={handleClose}>
                                        Assign Teacher To Class
                                    </DialogTitle>
                                    <DialogContent dividers>
                                        <Grid container spacing={3}>
                                            <Grid item xs={12}>
                                                <FormikSelectField
                                                    options={teachers}
                                                    variant="outlined"
                                                    margin="normal"
                                                    fullWidth
                                                    id="teacher_id"
                                                    label="Teacher"
                                                    name="teacher_id" />
                                            </Grid>
                                            <Grid item xs={12}>
                                                <FormikSelectField
                                                    options={options}
                                                    variant="outlined"
                                                    margin="normal"
                                                    fullWidth
                                                    id="class"
                                                    label="Class"
                                                    name="class" />
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
                                        {/* <Tooltip title="Assign Teacher To Class"> */}
                                            <Button
                                                type="submit"
                                                disabled={!(isValid && dirty)}
                                                color="primary">
                                                Assign Teacher
                                            </Button>
                                        {/* </Tooltip> */}
                                    </DialogActions>
                                </Form>
                            )}
                        </Formik>
                }
            </Dialog>
        </>
    );
}

export default AssignTeacherClass;
