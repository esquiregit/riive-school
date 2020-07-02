import React from 'react';
import PermScanWifiIcon from '@material-ui/icons/PermScanWifi';
import ReportOffOutlinedIcon from '@material-ui/icons/ReportOffOutlined';

function EmptyData({ error, single, plural }) {
    return (
        <div className="empty-data">
            {
                error
                ?
                <>
                    <PermScanWifiIcon />
                    <span>
                        <strong>network error!</strong>
                        &nbsp;server unreachable
                    </span>
                </>
                :
                <>
                    <ReportOffOutlinedIcon />
                    <span>
                        <strong>No {plural} {plural === 'Attendance' ? "Left For Today" : "Found!"}</strong>
                        &nbsp;
                        { plural === 'Activity Logs' || plural === 'Parents' || plural === 'Pickups' || plural === 'Attendances' || plural === 'Attendance' || plural === 'Securities' || plural === 'Assessments' ? null :
                            <span>click the "add {single}" button below to add one</span>
                        }
                    </span>
                </>
            }
        </div>
    )
}

export default EmptyData;
