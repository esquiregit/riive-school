import React, { useEffect, useState } from 'react';
import Grid from '@material-ui/core/Grid';
import clsx from 'clsx';
import Axios from 'axios';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import Footer from '../../../Layout/Footer';
import Header from '../../../Layout/Header';
import Loader from '../../../../Extras/Loadrr';
import Switch from '@material-ui/core/Switch';
import styles from '../../../../Extras/styles';
import Toastrr from '../../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Checkbox from '@material-ui/core/Checkbox';
import TextField from '@material-ui/core/TextField';
import Breadcrumb from '../../../Layout/Breadcrumb';
import Autocomplete from '@material-ui/lab/Autocomplete';
import CheckBoxIcon from '@material-ui/icons/CheckBox';
import CircularProgress from '@material-ui/core/CircularProgress';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import FormErrorMessage from '../../../../Extras/FormErrorMessage/FormErrorMessage';
import DialogContentText from '@material-ui/core/DialogContentText';
import CheckBoxOutlineBlankIcon from '@material-ui/icons/CheckBoxOutlineBlank';
import { Card } from '@material-ui/core';
import { getBaseURL } from '../../../../Extras/server';
import { getSidebar } from '../../../Layout/Sidebar';
import { useSelector } from 'react-redux';
import { DialogContent, DialogActions, DialogTitle, Transition } from '../../../../Extras/Dialogue';

const icon        = <CheckBoxOutlineBlankIcon fontSize="small" />;
const checkedIcon = <CheckBoxIcon fontSize="small" />;

function SMS({ history }) {
    const user    = useSelector(state => state.authReducer.user);
    const classes = styles();
    const sidebar = user && getSidebar(user.access_level);
    const visible = useSelector(state => state.sidebarReducer.visible);

    const [values, setValues] = useState([
        { message  : '' },
        { recipient: [] },
    ]);

    const [error, setError]                 = useState(false);
    const [loading, setLoading]             = useState(true);
    const [message, setMessage]             = useState('');
    const [parents, setParents]             = useState([]);
    const [success, setSuccess]             = useState(false);
    const [backdrop, setBackdrop]           = useState(false);
    const [comError, setComError]           = useState(false);
    const [formValid, setFormValid]         = useState(false);
    const [toAllParents, setToAllParents]   = useState(false);
    const [isConfirmOpen, setIsConfirmOpen] = useState(false);

    const [messageError, setMessageError]         = useState('');
    const [recipientError, setRecipientError]     = useState('');
    const [messageTouched, setMessageTouched]     = useState(false);
    const [recipientTouched, setRecipientTouched] = useState(false);

    useEffect(() => {
        document.title = 'RiiVe | SMS';
        const abortController = new AbortController();
        const signal = abortController.signal;

        if (user) {
            if (user.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL() + 'get_parents_message_phone_numbers', { school_id: user.id }, { signal: signal })
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
                Axios.post(getBaseURL() + 'get_parents_message_phone_numbers', { school_id: user.id, class: user.class }, { signal: signal })
                    .then(response => {
                        setParents(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setLoading(false);
                        setComError(true);
                        setMessage('Network Error. Server Unreachable....');
                    });
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [history, user]);
    const handleClose    = () => { setIsConfirmOpen(false); }
    const handleChange   = (event, newValue) => {
        if(event) {
            const name  = event.target.name;
            const value = event.target.value;
            
            let newArr  = [...values];
            if(newValue) {
                newArr[0]['recipient'] = newValue;
            } else if(name === 'message') {
                newArr[1]['message'] = value;
            }
            setValues(newArr);
            setFormValid(validateValues());
        }
    }
    const flipSwitch     = () => {
        const recipient  = values[0].recipient;
        const message    = values[1].message;
        
        if(recipient.length > 0 && message.length > 0) {
            setFormValid(true);
        } else {
            setFormValid(false);
        }
        setToAllParents(!toAllParents);
    }
    const validateValues = () => {
        let status       = true;
        const recipient  = values[0].recipient;
        const message    = values[1].message;
        
        if(toAllParents) {
            if(message.length === 0) {
                setMessageTouched(true);
                status = false;
                setMessageError('Please Type Message To Send');
            } else {
                setMessageTouched(true);
                setMessageError('');
            }
        } else {
            if(!recipient.length) {
                setRecipientTouched(true);
                status = false;
                setRecipientError('Please Select A Recipient');
            } else {
                setRecipientTouched(true);
                setRecipientError('');
            }
            if(message.length === 0) {
                setMessageTouched(true);
                status = false;
                setMessageError('Please Type Message To Send');
            } else {
                setMessageTouched(true);
                setMessageError('');
            }
        }
        
        return status;
    }
    const onConfirm      = (event) => {
        event.preventDefault();
        setIsConfirmOpen(true);
    };
    const onSubmit       = (action) => {
        setIsConfirmOpen(false);
        
        if(action.toLowerCase() === 'yes') {
            const recipientsArr = values[0].recipient;
            let recipients      = [];
            for (let index = 0; index < recipientsArr.length; index++) {
                recipients.push(recipientsArr[index].value);
            }
            
            const data       = {
                message      : values[1].message,
                recipient    : recipients,
                school_id    : user && user.access_level.toLowerCase() === 'school'  ? user.id : user.school_id,
                teacher_id   : user && user.access_level.toLowerCase() === 'teacher' ? user.id : '',
                access_level : user && user.access_level,
            };
            setError(false);
            setBackdrop(true);
            setComError(false);
            Axios.post(getBaseURL()+'send_sms', data)
                .then(response => {
                    if(response.data[0].status.toLowerCase() === 'success') {
                        setError(false);
                        resetForm();
                        setSuccess(true);
                        setMessage(response.data[0].message);
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
        }
    };
    const resetForm      = () => {
        setValues([
            { recipient: [] },
            { message  : '' }
        ]);
        setFormValid(false);
    }
    
    return (
        <>
            { error         && <Toastrr message={message} type="warning" /> }
            { comError      && <Toastrr message={message} type="info"    /> }
            { success       && <Toastrr message={message} type="success" /> }
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Sending SMS....</span>
            </Backdrop>
            { isConfirmOpen &&
                <div>
                    <Dialog
                        open={isConfirmOpen}
                        keepMounted
                        onClose={handleClose}
                        disableBackdropClick={true}
                        disableEscapeKeyDown={true}
                        TransitionComponent={Transition}
                        aria-labelledby="alert-dialog-slide-title"
                        aria-describedby="alert-dialog-slide-description" >
                        <DialogTitle id="alert-dialog-slide-title">Confirm Action</DialogTitle>
                        <DialogContent>
                            <DialogContentText id="alert-dialog-slide-description">
                                Are You Sure You Want To Send SMS?
                            </DialogContentText>
                        </DialogContent>
                        <DialogActions>
                            <Button onClick={() => onSubmit('No')} color="primary">
                                No
                            </Button>
                            <Button onClick={() => onSubmit('Yes')} color="secondary">
                                Yes
                            </Button>
                        </DialogActions>
                    </Dialog>
                </div>
            }
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="SMS" />
                {
                    loading
                    ?
                    <Loader />
                    :
                    <Card className="box-shadow p-20">
                        <form onSubmit={onConfirm} className="message-form">
                            <Grid container spacing={3}>
                                <Grid item xs={12}>
                                <FormControlLabel
                                    control={
                                        <Switch
                                            checked={toAllParents}
                                            onChange={flipSwitch}
                                            name="To All Parents"
                                            inputProps={{ 'aria-label': 'To All Parents' }} />
                                    }
                                    label="To All Parents" />
                                </Grid>
                                <Grid item xs={12}>
                                    <Autocomplete
                                        onChange={(e, newValue) => handleChange(e, newValue)}
                                        disabled={toAllParents}
                                        value={values[0].recipient}
                                        multiple
                                        id="recipient"
                                        options={parents}
                                        disableCloseOnSelect
                                        getOptionLabel={(option) => option.label}
                                        renderOption={(option, { selected }) => (
                                            <React.Fragment>
                                                <Checkbox
                                                    icon={icon}
                                                    checkedIcon={checkedIcon}
                                                    style={{ marginRight: 8 }}
                                                    checked={selected}
                                                />
                                                {option.label}
                                            </React.Fragment>
                                        )}
                                        renderInput={(params) => (
                                            <TextField {...params} variant="outlined" label="Recipient" placeholder="Recipient" />
                                        )}
                                    />
                                    {!toAllParents && recipientTouched && recipientError && <FormErrorMessage className="mt-30" message={recipientError} />}
                                </Grid>
                                <Grid item xs={12}>
                                    <TextField
                                        onChange={e => handleChange(e)}
                                        value={values[1].message}
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
                                    {messageError && messageTouched && <FormErrorMessage message={messageError} />}
                                </Grid>
                                <Grid item xs={12} className="text-centre">
                                    <Button
                                        disabled={!formValid}
                                        size="large"
                                        type="submit"
                                        variant="contained"
                                        color="primary"
                                        className='text-capitalise'>
                                        Send SMS
                                    </Button>
                                </Grid>
                            </Grid>
                        </form>
                    </Card>
                }
            </main>
            <Footer />
        </>
    )
}

export default SMS;
