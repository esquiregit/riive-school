import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import Tooltip from '@material-ui/core/Tooltip';
import BlockIcon from '@material-ui/icons/Block';
import GetAppIcon from '@material-ui/icons/GetApp';
import ReplayIcon from '@material-ui/icons/Replay';
import TeacherPDF from './TeacherPDF';
import EditTeacher from './EditTeacher';
import EditOutlinedIcon from '@material-ui/icons/EditOutlined';
import CircularProgress from '@material-ui/core/CircularProgress';
import { getBaseURL } from '../../../Extras/server';
import { makeStyles } from '@material-ui/core/styles';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

const useStyles = makeStyles((theme) => ({
    backdrop: {
      zIndex: theme.zIndex.drawer + 1,
      color: '#fff',
    },
}));

function ViewTeacher({ length, teacher, closeExpandable }) {//console.log('view teacher render')
    const names                     = teacher.name.split(' ');
    const classes                   = useStyles();
    const filename                  = teacher.name + ".pdf";
    const editTooltip               = "Edit " + teacher.name + "'s Details";
    const blockTooltip              = "Block " + teacher.name;
    const unblockTooltip            = "Unblock " + teacher.name;
    const downloadTooltip           = "Download " + teacher.name + "'s Details";
    const [error, setError]         = useState(false);
    const [success, setSuccess]     = useState(false);
    const [message, setMessage]     = useState('');
    const [backdrop, setBackdrop]   = useState(false);
    const [comError, setComError]   = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [backdropMessage, setBackdropMessage] = useState('');
    const closeModal                = (action, message) => {
        setShowModal(false);
        action && action.toLowerCase() === 'reload' && closeExpandable(message);
    }
    const blockTeacher              = () => {
        setError(false);
        setBackdrop(true);
        setSuccess(false);
        setComError(false);
        setBackdropMessage('Blocking Teacher....');
        Axios.post(getBaseURL() + 'block_teacher', { teacher_id: teacher.id, schoolCode: teacher.school_id })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setError(false);
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    setTimeout(() => closeExpandable(), 2000);
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
    const unblockTeacher            = () => {
        setError(false);
        setBackdrop(true);
        setSuccess(false);
        setComError(false);
        setBackdropMessage('Unblocking Teacher....');
        Axios.post(getBaseURL() + 'unblock_teacher', { teacher_id: teacher.id, schoolCode: teacher.school_id })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setError(false);
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    setTimeout(() => closeExpandable(), 2000);
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
        <TableRow>
            <TableCell colSpan={length + 1}>
                <div className="detail-div">
                    { showModal && <EditTeacher teacherData={teacher} closeModal={closeModal} />}
                    { error     && <Toastrr message={message} type="warning" />}
                    { comError  && <Toastrr message={message} type="info" />}
                    { success   && <Toastrr message={message} type="success" />}
                    <Backdrop className={classes.backdrop} open={backdrop}>
                        <CircularProgress color="inherit" /> <span className='ml-15'>{backdropMessage}</span>
                    </Backdrop>
                    <table id="detail-table">
                        <tbody>
                            <tr>
                                <td width="30%" rowSpan="6">
                                    <img
                                        src={getBaseURL() + teacher.image}
                                        alt={teacher.name + '\'s Image'} />
                                </td>
                                <th width="25%">First Name: </th>
                                <td width="45%">{names[0]}</td>
                            </tr>
                            <tr>
                                <th>Last Name: </th>
                                <td>{names.length === 3 ? names[2] : names[1]}</td>
                            </tr>
                            <tr>
                                <th>Other Name: </th>
                                <td>{names.length === 3 ? names[1] : null}</td>
                            </tr>
                            <tr>
                                <th>Phone Number: </th>
                                <td>{teacher.contact}</td>
                            </tr>
                            <tr>
                                <th>Email Address: </th>
                                <td>{teacher.email}</td>
                            </tr>
                        </tbody>
                    </table>

                    <Grid className="table-detail-toolbar" container spacing={0}>
                        <Grid item xs={4}>
                            <BlobProvider
                                document={<TeacherPDF teacher={teacher} names={names} />}
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
                            <Tooltip title={editTooltip}>
                                <IconButton onClick={() => setShowModal(true)}>
                                    <EditOutlinedIcon color="primary" />
                                </IconButton>
                            </Tooltip>
                            {
                                teacher.status.toLowerCase() === 'active' ?
                                    <Tooltip title={blockTooltip}>
                                        <IconButton onClick={blockTeacher}>
                                            <BlockIcon color="secondary" />
                                        </IconButton>
                                    </Tooltip> :
                                    <Tooltip title={unblockTooltip}>
                                        <IconButton onClick={unblockTeacher}>
                                            <ReplayIcon className="colour-success" />
                                        </IconButton>
                                    </Tooltip>
                            }
                        </Grid>
                    </Grid>
                </div>
            </TableCell>
        </TableRow>
    )
}

export default ViewTeacher;
