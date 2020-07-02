import React from 'react';
import Grid from '@material-ui/core/Grid';
import Tooltip from '@material-ui/core/Tooltip';
import GetAppIcon from '@material-ui/icons/GetApp';
import SecurityPDF from './SecurityPDF';
import EditSecurity from './EditSecurity';
import EditOutlinedIcon from '@material-ui/icons/EditOutlined';
import { BlobProvider } from "@react-pdf/renderer";
import { TableRow, TableCell, IconButton } from '@material-ui/core';

function ViewSecurity({ length, security, closeExpandable }) {
    const filename        = security.name+".pdf";
    const downloadTooltip = "Download "+security.name+"'s Details";
    const editTooltip     = "Edit "+security.name+"'s Details";
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
                        { showModal && <EditSecurity security={security} closeModal={closeModal} /> }
                        <table id="detail-table">
                            <tbody>
                                <tr>
                                    <th>Security: </th>
                                    <td>{security.name}</td>
                                </tr>
                                <tr>
                                    <th>Contact: </th>
                                    <td>{security.contact}</td>
                                </tr>
                                <tr>
                                    <th>Account Type: </th>
                                    <td>{security.accountType}</td>
                                </tr>
                                <tr>
                                    <th>School: </th>
                                    <td>{security.schoolname}</td>
                                </tr>
                            </tbody>
                        </table>

                        <Grid className="table-detail-toolbar" container spacing={0}>
                            <Grid item xs={4}>
                                <BlobProvider
                                    document={<SecurityPDF security={security} />}
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
                            </Grid>
                        </Grid>
                    </div>
                </TableCell>
            </TableRow>
        </>
    )
}

export default ViewSecurity;
