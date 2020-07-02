import React from 'react';
import Grid from '@material-ui/core/Grid';
import Tooltip from '@material-ui/core/Tooltip';
import VisitorPDF from './VisitorPDF';
import GetAppIcon from '@material-ui/icons/GetApp';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewVisitor({ length, visitor }) {
    const filename        = visitor.visitorName+".pdf";
    const downloadTooltip = "Download "+visitor.visitorName+"'s Visit Details";
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <td width="30%" rowSpan="7">
                                        <img
                                            width="100"
                                            height="270"
                                            src={visitor.imagePath + '/' + visitor.image}
                                            alt={visitor.visitorName + '\'s Image'} />
                                    </td>
                                    <th width="28%">Visitor: </th>
                                    <td width="42%">{visitor.visitorName}</td>
                                </tr>
                                <tr>
                                    <th>Person To Visit: </th>
                                    <td>{visitor.personToVisit}</td>
                                </tr>
                                <tr>
                                    <th>Purpose of Visit: </th>
                                    <td>{visitor.purposeOfVisit}</td>
                                </tr>
                                <tr>
                                    <th>Security Personnel: </th>
                                    <td>{visitor.name}</td>
                                </tr>
                                <tr>
                                    <th>Visitor Phone Number: </th>
                                    <td>{visitor.visitorNumber}</td>
                                </tr>
                                <tr>
                                    <th>Clock In Time: </th>
                                    <td>{visitor.clockInTime}</td>
                                </tr>
                                <tr>
                                    <th>Clock Out Time: </th>
                                    <td>{visitor.clockOutTime}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<VisitorPDF visitor={visitor} />}
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

export default ViewVisitor;
