import React from 'react';
import Grid from '@material-ui/core/Grid';
import moment from 'moment';
import Tooltip from '@material-ui/core/Tooltip';
import GetAppIcon from '@material-ui/icons/GetApp';
import StudentPDF from './StudentPDF';
import EditStudent from './EditStudent';
import EditOutlinedIcon from '@material-ui/icons/EditOutlined';
import { getBaseURL } from '../../../Extras/server';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewStudent({ length, student, access_level, closeExpandable }) {
    const filename        = student.name+".pdf";
    const downloadTooltip = "Download "+student.firstname+"'s Details";
    const editTooltip     = "Edit "+student.firstname+"'s Details";
    const [showModal, setShowModal] = React.useState(false);
    const closeModal                = (action, message) => {
        setShowModal(false);
        action && action.toLowerCase() === 'reload' && closeExpandable(message);
    }
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        { showModal && <EditStudent student={student} closeModal={closeModal} /> }
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <td width="30%" rowSpan="7">
                                        <img
                                            width="100"
                                            height="270"
                                            src={getBaseURL() + student.imagePath + '/' + student.image}
                                            alt={student.name + '\'s Image'} />
                                    </td>
                                    <th width="28%">First Name: </th>
                                    <td width="42%">{student.firstname}</td>
                                </tr>
                                <tr>
                                    <th>Last Name: </th>
                                    <td>{student.lastname}</td>
                                </tr>
                                <tr>
                                    <th>Other Name: </th>
                                    <td>{student.othernames}</td>
                                </tr>
                                <tr>
                                    <th>Class: </th>
                                    <td>{student.class}</td>
                                </tr>
                                <tr>
                                    <th>Gender: </th>
                                    <td>{student.gender}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth: </th>
                                    <td>{moment(student.dob).format('dddd, Do MMMM YYYY')}</td>
                                </tr>
                                <tr>
                                    <th>Student Code: </th>
                                    <td>{student.studentCode}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<StudentPDF student={student} />}
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
                            {
                                access_level.toLowerCase() === 'school' &&
                                <Grid item xs={8} className="text-right">
                                    <Tooltip title={editTooltip}>
                                        <IconButton onClick={() => setShowModal(true)}>
                                            <EditOutlinedIcon color="primary" />
                                        </IconButton>
                                    </Tooltip>
                                </Grid>
                            }
                        </Grid>
                    </div>
                </TableCell>
            </TableRow>
        </>
    )
}

export default ViewStudent;
