import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../Extras/styles';
import Footer from '../Layout/Footer';
import Header from '../Layout/Header';
import Loader from '../../Extras/Loadrr';
import EmptyData from '../../Extras/EmptyData';
import Breadcrumb from '../Layout/Breadcrumb';
import MUIDataTable from "mui-datatables";
import SidebarSchool from '../Layout/SidebarSchool';
import { getBack } from '../../Extras/GoBack';
import { getBaseURL } from '../../Extras/server';
import { useSelector } from 'react-redux';

function AuditTrail({ history }) {
    const classes = styles();
    const school  = useSelector(state => state.authReducer.user);

    const [logs, setLogs]           = useState(true);
    const [loading, setLoading]     = useState(true);
    const [comError, setComError]   = useState(false);

    useEffect(()    => {
        document.title        = 'RiiVe | Acitivity Log';
        const abortController = new AbortController();
        const signal          = abortController.signal;
        
        if(school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_activity_logs', { school_id: school.id }, { signal: signal })
                    .then(response => {
                        setLogs(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else {
                getBack(history);
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [school, history, loading]);
    let rowsPerPage = [];
    const visible   = useSelector(state => state.sidebarReducer.visible);
    const columns   = [
        {
            label: "Name",
            name: "name",
            options: {
                filter: true,
            }
        },
        {
            label: "Access Level",
            name: "access_level",
            options: {
                filter: true,
            }
        },
        {
            label: "Activity",
            name: "activity",
            options: {
                filter: true,
            }
        },
        {
            label: "Date",
            name: "date",
            options: {
                filter: true,
            }
        },
    ];
    if (logs) {
        if (logs.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, logs.length];
        }
    } else {
        rowsPerPage = [10, 25, 50, 100];
    }
    const options = {
        filter: true,
        filterType: 'dropdown',
        responsive: 'standard',
        pagination: true,
        rowsPerPageOptions: rowsPerPage,
        resizableColumns: false,
        downloadOptions: { filename: 'Activity Log.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Activity Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Activity Log",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Activity Log",
            }
        }
    };
    return (
        <>
            <Header user={school} />
            <SidebarSchool />
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Activity Log" />
                {
                    loading ? <Loader /> :
                        (logs && logs.length)
                            ?
                            <MUIDataTable
                                data={logs}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Activity Log" plural="Activity Logs" />
                }
            </main>
            <Footer />
        </>
    );
}

export default AuditTrail;
