import React from 'react';
import ReportOffOutlinedIcon from '@material-ui/icons/ReportOffOutlined';

function NoClassAssigned({ type }) {
    return (
        <div className="empty-data">
            <>
                <ReportOffOutlinedIcon />
                <span>
                    <strong>No {type} Found!&nbsp;You Haven't Been Assigned A Class Yet</strong>
                </span>
            </>
        </div>
    )
}

export default NoClassAssigned;
