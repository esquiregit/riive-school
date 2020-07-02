import React, { useState } from 'react';
import Grid from '@material-ui/core/Grid';
import Tooltip from '@material-ui/core/Tooltip';
import GetAppIcon from '@material-ui/icons/GetApp';
import AssessmentPDF from './AssessmentPDF';
import EditAssessment from './EditAssessment';
import EditOutlinedIcon from '@material-ui/icons/EditOutlined';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewAssessment({ length, assessment, access_level, school_id, teacher_id, closeExpandable }) {
    const filename                  = assessment.student+"_Assessment.pdf";
    const downloadTooltip           = "Download "+assessment.student+"'s Assessment";
    const editTooltip               = "Edit " + assessment.student + "'s Assessment";
    const [showModal, setShowModal] = useState(false);
    const closeModal                = (action, message) => {
        action && action.toLowerCase() === 'reload' && setShowModal(false);
        action && action.toLowerCase() === 'reload' && closeExpandable(message);
    }
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        { showModal && <EditAssessment
                            school_id={school_id}
                            teacher_id={teacher_id}
                            assessment={assessment}
                            closeModal={closeModal} />}
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <th>Student: </th>
                                    <td>{assessment.student}</td>
                                </tr>
                                <tr>
                                    <th>Class: </th>
                                    <td>{assessment.class}</td>
                                </tr>
                                <tr>
                                    <th>Academic Year: </th>
                                    <td>{assessment.academic_year}</td>
                                </tr>
                                <tr>
                                    <th>Term: </th>
                                    <td>{assessment.term}</td>
                                </tr>
                                <tr>
                                    <th>Subject: </th>
                                    <td>{assessment.subject}</td>
                                </tr>
                                <tr>
                                    <th>Class Test: </th>
                                    <td>{assessment.class_tests}</td>
                                </tr>
                                <tr>
                                    <th>Assignments: </th>
                                    <td>{assessment.assignments}</td>
                                </tr>
                                <tr>
                                    <th>Interim Assessment: </th>
                                    <td>{assessment.interim_assessment}</td>
                                </tr>
                                <tr>
                                    <th>Attendance: </th>
                                    <td>{assessment.attendance_mark}</td>
                                </tr>
                                <tr>
                                    <th>Exams: </th>
                                    <td>{assessment.exams_score}</td>
                                </tr>
                                <tr>
                                    <th>Total Score: </th>
                                    <td>{assessment.total_score}</td>
                                </tr>
                                <tr>
                                    <th>Grade: </th>
                                    <td>{assessment.grade}</td>
                                </tr>
                                <tr>
                                    <th>Remarks: </th>
                                    <td>{assessment.remarks}</td>
                                </tr>
                                <tr>
                                    <th>Date: </th>
                                    <td>{assessment.date_entered}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<AssessmentPDF assessment={assessment} />}
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
                            { access_level.toLowerCase() === 'teacher' &&
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

export default ViewAssessment;
