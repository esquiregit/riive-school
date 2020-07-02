import React from 'react';
import Grid from '@material-ui/core/Grid';
import Tooltip from '@material-ui/core/Tooltip';
import ParentPDF from './ParentPDF';
import GetAppIcon from '@material-ui/icons/GetApp';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewParent({ length, parent }) {
    const filename        = parent.student+"_"+parent.relation+".pdf";
    const downloadTooltip = "Download "+parent.student+"'s "+parent.relation+"'s Details";
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <th>Parent: </th>
                                    <td>{parent.parent}</td>
                                </tr>
                                <tr>
                                    <th>Student: </th>
                                    <td>{parent.student}</td>
                                </tr>
                                <tr>
                                    <th>Class: </th>
                                    <td>{parent.class}</td>
                                </tr>
                                <tr>
                                    <th>Relation: </th>
                                    <td>{parent.relation}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number: </th>
                                    <td>{parent.phone}</td>
                                </tr>
                                <tr>
                                    <th>Email Address: </th>
                                    <td>{parent.email}</td>
                                </tr>
                                <tr>
                                    <th>Location: </th>
                                    <td>{parent.location}</td>
                                </tr>
                                <tr>
                                    <th>Occupation: </th>
                                    <td>{parent.occupation}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<ParentPDF parent={parent} />}
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
                        </Grid>
                    </div>
                </TableCell>
            </TableRow>
        </>
    )
}

export default ViewParent;
