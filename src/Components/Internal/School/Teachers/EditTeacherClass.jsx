import React, { useState } from 'react';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Toastrr from '../../../Extras/Toastrr';
import Tooltip from '@material-ui/core/Tooltip';
import LoaderInnerRow from '../../../Extras/LoadrrInnerRow';
import SaveOutlinedIcon from '@material-ui/icons/SaveOutlined';
import { getBaseURL } from '../../../Extras/server';
import { Form, Formik } from 'formik';
import { classesOptions } from '../../../Extras/ClassesOptions';
import { TableRow, TableCell } from '@material-ui/core';
import { FormikSelectField, FormikTextField } from 'formik-material-fields';
import * as Yup from 'yup';

const validationSchema = Yup.object().shape({
    class: Yup
        .string()
        .required('Please Select Class To Assign Teacher To')
});

function EditTeacherClass({ setInnerSuccess, schoolId, length, assignmentData }) {
    const options                 = classesOptions();
    const [error, setError]       = useState(false);
    const [loading, setLoading]   = useState(false);
    const [warning, setWarning]   = useState(false);
    const [comError, setComError] = useState(false);
    const [errorMsg, setErrorMsg] = useState(false);
    const initialValues           = {
        id: assignmentData.id,
        teacher_id: assignmentData.teacher_id,
        name: assignmentData.name,
        class: assignmentData.class,
        school_id: schoolId
    }
    const onSubmit                = values => {
        setLoading(true);
        setError(false);
        setInnerSuccess(false, null);
        setComError(false);
        Axios.post(getBaseURL() + 'update_assigned_teacher_class', values)
            .then(response => {
                setLoading(false);
                if (response.data[0].status.toLowerCase() === 'success') {
                    setInnerSuccess(true, response.data[0].message);
                    setError(false);
                    setWarning(false);
                } else if (response.data[0].status.toLowerCase() === 'warning') {
                    setWarning(true);
                    setError(false);
                    setErrorMsg(response.data[0].message);
                    setInnerSuccess(false, null);
                } else {
                    setError(true);
                    setErrorMsg(response.data[0].message);
                    setInnerSuccess(false, null);
                }
            })
            .catch(error => {
                setLoading(false);
                setComError(true);
            });
    }

    return (
        <TableRow className="inner-row">
            <TableCell colSpan={length + 1}>
                {warning  && <Toastrr message={errorMsg} type="warning" />}
                {error    && <Toastrr message={errorMsg} type="error" />}
                {comError && <Toastrr message="Network Error. Server Unreachable" type="info" />}
                {
                    loading ? <LoaderInnerRow /> :
                        <Formik
                            initialValues={initialValues}
                            validationSchema={validationSchema}
                            onSubmit={onSubmit} >
                            {({isValid, dirty}) => (
                                <div className=''>
                                    <Form className="text-right">
                                        <FormikTextField
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="name"
                                            label="Teacher"
                                            placeholder="Teacher"
                                            name="name"
                                            disabled />
                                        <FormikSelectField
                                            options={options}
                                            variant="outlined"
                                            margin="normal"
                                            fullWidth
                                            id="class"
                                            label="Class"
                                            name="class" />
                                        <Tooltip title="Update Assignment">
                                            <Button
                                                disabled={!(isValid && dirty)}
                                                size="large"
                                                type="submit"
                                                variant="contained"
                                                color="primary"
                                                className='text-capitalise mt-10 mr-1'
                                                disableElevation>
                                                <SaveOutlinedIcon className="white" />
                                            </Button>
                                        </Tooltip>
                                    </Form>
                                </div>
                            )}
                        </Formik>
                }
            </TableCell>
        </TableRow>
    )
}

export default EditTeacherClass;
