import React from 'react';
import Grid from '@material-ui/core/Grid';
import Button from '@material-ui/core/Button';
import { Card } from '@material-ui/core';
import { Form, Formik } from 'formik';
import { classesOptions } from '../../../../Extras/ClassesOptions';
import { FormikTextField, FormikSelectField } from 'formik-material-fields';
import * as Yup from 'yup';

function EmailSchool() {
    const initialValues = {
        recipient: '',
        subject: '',
        attachment: '',
        message: '',
    };
    const validationSchema = Yup.object().shape({
        recipient: Yup
            .string()
            .required('Please Select At Least One (1) Recipient'),
        message: Yup
            .string()
            .required('Please Enter Message To Send')
            .max(120, 'Message Cannot Be More Than 120 Characters'),
    });
    const onSubmit = () => {

    };
    const classOptions = classesOptions();

    return (
        <>
            <Card className="box-shadow p-20">
                <Formik
                    initialValues={initialValues}
                    validationSchema={validationSchema}
                    onSubmit={onSubmit}>
                    {(props) => (
                        <Form className="message-form">
                            <Grid container spacing={3}>
                                <Grid item xs={12}>
                                    <FormikSelectField
                                        options={classOptions}
                                        variant="outlined"
                                        margin="normal"
                                        fullWidth
                                        id="recipient"
                                        label="Recipient"
                                        name="recipient" />
                                </Grid>
                                <Grid item xs={12} sm={6}>
                                    <FormikTextField
                                        variant="outlined"
                                        margin="normal"
                                        fullWidth
                                        id="subject"
                                        label="Subject"
                                        placeholder="Subject"
                                        name="subject" />
                                </Grid>
                                <Grid item xs={12} sm={6}>
                                    <FormikTextField
                                        variant="outlined"
                                        margin="normal"
                                        fullWidth
                                        className="hidde"
                                        id="attachment"
                                        name="attachment"
                                        type="file" />
                                </Grid>
                                <Grid item xs={12}>
                                    <FormikTextField
                                        multiline
                                        rows={4}
                                        variant="outlined"
                                        margin="normal"
                                        fullWidth
                                        id="message"
                                        label="Message"
                                        name="message" />
                                </Grid>
                            </Grid>
                        </Form>
                    )}
                </Formik>
            </Card>
        </>
    )
}

export default EmailSchool;
