import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Tooltip from '@material-ui/core/Tooltip';
import GetAppIcon from '@material-ui/icons/GetApp';
import AttendancePDF from './AttendancePDF';
import EditAttendance from './EditAttendance';
import ConfirmDialogue from '../../../Extras/ConfirmDialogue';
import CircularProgress from '@material-ui/core/CircularProgress';
import DoneOutlinedIcon from '@material-ui/icons/DoneOutlined';
import EditOutlinedIcon from '@material-ui/icons/EditOutlined';
import { getBaseURL } from '../../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { useSelector } from 'react-redux';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function ViewAttendance({ history, length, attendance, access_level, closeExpandable }) {//console.log(attendance)
    const user                            = useSelector(state => state.authReducer.user);
    const classes                         = useStyles();
    const filename                        = attendance.name+".pdf";
    const downloadTooltip                 = "Download "+attendance.student+"'s Attendance";
    const editTooltip                     = "Edit "+attendance.student+"'s Attendance";
    const clockOutTooltip                 = "Clock Out "+attendance.student;
    const [error, setError]               = useState(false);
    const [message, setMessage]           = useState('');
    const [success, setSuccess]           = useState(false);
    const [backdrop, setBackdrop]         = useState(false);
    const [comError, setComError]         = useState(false);
    const [showModal, setShowModal]       = useState(false);
    const [showDialogue, setShowDialogue] = useState(false);
    const closeModal                      = (action, message, result) => {
        setShowModal(false);
        setShowDialogue(false);
        action && action.toLowerCase() === 'reload' && closeExpandable(message);

        if(result && result.toLowerCase() === 'yes') {
            clockOutStudent();
        }
    }
    const clockOutStudent                 = () => {
        setBackdrop(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;
        const values          = {
            id: attendance.id,
            class: user.class,
            name: user.name,
            student: attendance.student,
            student_id: attendance.student_id,
            pickUpCode: attendance.pickUpCode,
            access_level: user.access_level,
            teacher_id: user && user.access_level.toLowerCase() === 'teacher' ? user.id : null,
            schoolCode: user && user.access_level.toLowerCase() === 'school' ? user.id : user.school_id
        }

        if(user) {
            Axios.post(getBaseURL()+'clock_out_student', values, { signal: signal })
                .then(response => {
                    console.log(response.data)
                    console.log(response.data[0].status)
                    console.log(response.data[0].message)
                    if(response.data[0].status.toLowerCase() === 'success') {
                        setMessage(response.data[0].message);
                        setSuccess(true);
                        setTimeout(() => {
                        closeModal('Reload', response.data[0].message, null);
                        closeExpandable(response.data[0].message);
                        }, 2000);
                    } else {
                        setError(true);
                        setMessage(response.data[0].message);
                    }
                    setBackdrop(false);
                })
                .catch(error => {
                    setBackdrop(false);
                    setMessage('Network Error. Server Unreachable....');
                    setComError(true);
                });
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        { showDialogue && <ConfirmDialogue message={'Are You Sure You Want To Clock Out '+attendance.student+'?'} closeModal={closeModal} /> }
                        { showModal    && <EditAttendance  length={length} attendance={attendance} closeModal={closeModal} /> }
                        { error        && <Toastrr message={message} type="warning" />}
                        { success      && <Toastrr message={message} type="success" />}
                        { comError     && <Toastrr message={message} type="info" />}
                        <Backdrop className={classes.backdrop} open={backdrop}>
                            <CircularProgress color="inherit" /> <span className='ml-15'>Clocking Student Out....</span>
                        </Backdrop>
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <td width="30%" rowSpan="7">
                                        <img
                                            width="100"
                                            height="270"
                                            src={getBaseURL() + attendance.imagePath + '/' + attendance.image}
                                            alt={attendance.student + '\'s Image'} />
                                    </td>
                                    <th width="28%">Student: </th>
                                    <td width="42%">{attendance.student}</td>
                                </tr>
                                <tr>
                                    <th>Date: </th>
                                    <td>{attendance.date}</td>
                                </tr>
                                <tr>
                                    <th>Status: </th>
                                    <td>{attendance.status}</td>
                                </tr>
                                <tr>
                                    <th>Clock In Time: </th>
                                    <td>{attendance.clock_in_time}</td>
                                </tr>
                                <tr>
                                    <th>Clock Out Time: </th>
                                    <td>{attendance.clock_out_time}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Code: </th>
                                    <td>{attendance.pickupCode}</td>
                                </tr>
                                <tr>
                                    <th>Class: </th>
                                    <td>{attendance.class}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<AttendancePDF attendance={attendance} />}
                                    filename={filename}
                                    style={{
                                        textDecoration: "none",
                                    }}>
                                        {({url}) => (
                                            <a href={url} target="_blank" rel="noopener noreferrer" >
                                                <Tooltip title={downloadTooltip}>
                                                    <IconButton>
                                                        <GetAppIcon className="colour-success" />
                                                    </IconButton>
                                                </Tooltip>
                                            </a>
                                        )}
                                </BlobProvider>
                            </Grid>
                            <Grid item xs={8} className="text-right">
                                {
                                    access_level.toLowerCase() === 'teacher' && attendance.clock_out_time === '--:--:--' &&
                                    <Tooltip title={editTooltip}>
                                        <IconButton onClick={() => setShowModal(true)}>
                                            <EditOutlinedIcon color="primary" />
                                        </IconButton>
                                    </Tooltip>
                                }
                                {
                                    attendance.status === 'Present' && attendance.clock_out_time === '--:--:--' &&
                                    <Tooltip title={clockOutTooltip}>
                                        <IconButton onClick={() => setShowDialogue(true)}>
                                            <DoneOutlinedIcon className="colour-success" />
                                        </IconButton>
                                    </Tooltip>
                                }
                            </Grid>
                        </Grid>
                    </div>
                </TableCell>
            </TableRow>
        </>
    )
}

export default ViewAttendance;
