import React from 'react';
import Typography from '@material-ui/core/Typography';
import Breadcrumbs from '@material-ui/core/Breadcrumbs';

function Breadcrumb({ page }) {
    return (
        <div className="breadcrumb-bar">
            <Breadcrumbs aria-label="breadcrumb">
                <Typography color="textPrimary">Home</Typography>
                <Typography color="textPrimary">{page}</Typography>
            </Breadcrumbs>
        </div>
    )
}

export default Breadcrumb;
