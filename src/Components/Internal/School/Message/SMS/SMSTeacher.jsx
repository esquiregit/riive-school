import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Loader from '../../../../Extras/Loadrr';
import Select from '@material-ui/core/Select';
import Toastrr from '../../../../Extras/Toastrr';
import InputLabel from '@material-ui/core/InputLabel';
import FormControl from '@material-ui/core/FormControl';
import { Card } from '@material-ui/core';
import { getBaseURL } from '../../../../Extras/server';
import { useSelector } from 'react-redux';
import { Form, Formik } from 'formik';
import { FormikTextField, FormikSelectField } from 'formik-material-fields';
import * as Yup from 'yup';

function SMSTeacher({ history }) {
    const user             = useSelector(state => state.authReducer.user);
    const initialValues = {
        recipient: '',
        message: '',
    };
    const validationSchema = Yup.object().shape({
        recipient: Yup
            .string()
            .required('Please Select A Recipient'),
        message: Yup
            .string()
            .required('Please Enter Message To Send')
            .max(120, 'Message Cannot Be More Than 120 Characters'),
    });
    const onSubmit = () => {

    };
    const [parents, setParents]   = useState([]);
    const [loading, setLoading]   = useState(true);
    const [message, setMessage]   = useState('');
    const [comError, setComError] = useState(false);
    React.useEffect(()                  => {
        const abortController     = new AbortController();
        const signal              = abortController.signal;

        if(user) {
            Axios.post(getBaseURL()+'get_parents_message_info', { school_id: user.id, class: user.class }, { signal: signal })
                .then(response => {
                    setParents(response.data);
                    setLoading(false);
                })
                .catch(error => {
                    setLoading(false);
                    setComError(true);
                    setMessage('Network Error. Server Unreachable....');
                });
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, []);

    return (
        <>
            { comError && <Toastrr message={message} type="info" />}
            {
                loading ? <Loader /> :
                <Card className="box-shadow p-20">
                    <Formik
                        initialValues={initialValues}
                        validationSchema={validationSchema}
                        onSubmit={onSubmit}>
                        {(props) => (
                            <Form className="message-form">
                                <Grid container spacing={3}>
                                    <Grid item xs={12}>
                                        {/* <FormikSelectField
                                            options={parents.sort()}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="recipient"
                                            label="Recipient"
                                            name="recipient" /> */}
                                        <FormControl fullWidth className="mt-10">
                                            <InputLabel
                                                htmlFor="recipient"
                                                className="pl-15">Recipient</InputLabel>
                                            <Select
                                                variant="outlined"
                                                margin="none"
                                                native
                                                defaultValue=""
                                                id="recipient"
                                                name="recipient">
                                                <option aria-label="None" value="" />
                                                {
                                                    parents.map(parent => {
                                                        return (
                                                            <option key={parent.value} value={parent.value}>{parent.label}</option>
                                                        );
                                                    })
                                                }
                                            </Select>
                                        </FormControl>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <FormikTextField
                                            multiline
                                            rows={3}
                                            InputProps={{ inputProps: { maxLength: 120 } }}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="message"
                                            label="Message - 120 Characters Max"
                                            placeholder="Message - 120 Characters Max...."
                                            name="message" />
                                    </Grid>
                                    <Grid item xs={12} className="text-centre">
                                        <Button
                                            size="large"
                                            type="submit"
                                            variant="contained"
                                            color="primary"
                                            className='text-capitalise'>
                                            Send SMS
                                        </Button>
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

export default SMSTeacher;
