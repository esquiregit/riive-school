import React from 'react';
import Grid from '@material-ui/core/Grid';
import Tooltip from '@material-ui/core/Tooltip';
import PickupPDF from './PickupPDF';
import GetAppIcon from '@material-ui/icons/GetApp';
import { getBaseURL } from '../../../Extras/server';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewPickup({ length, pickup }) {
    const filename        = pickup.student+"_Pickup.pdf";
    const downloadTooltip = "Download "+pickup.student+"'s Pickup Details";
    
    return (
        <>
            <TableRow>
                <TableCell colSpan={length + 1}>
                    <div className="detail-div">
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <td width="30%" rowSpan="8">
                                        <img
                                            width="100"
                                            height="270"
                                            src={getBaseURL() + pickup.imagePath + '/' + pickup.image}
                                            alt={pickup.pickUpPerson + '\'s Image'} />
                                    </td>
                                    <th width="30%">Pickup Person: </th>
                                    <td width="40%">{pickup.pickUpPerson}</td>
                                </tr>
                                <tr>
                                    <th>Student: </th>
                                    <td>{pickup.student}</td>
                                </tr>
                                <tr>
                                    <th>Class: </th>
                                    <td>{pickup.class}</td>
                                </tr>
                                <tr>
                                    <th>Sent By: </th>
                                    <td>{pickup.parent}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Code: </th>
                                    <td>{pickup.code}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Type: </th>
                                    <td>{pickup.type}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Person's Number: </th>
                                    <td>{pickup.phone}</td>
                                </tr>
                                <tr>
                                    <th>Date: </th>
                                    <td>{pickup.date}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<PickupPDF pickup={pickup} />}
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

export default ViewPickup;
