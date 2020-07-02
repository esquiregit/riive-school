import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../../Extras/styles';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import Toastrr from '../../../Extras/Toastrr';
import EmptyData from '../../../Extras/EmptyData';
import Breadcrumb from '../../Layout/Breadcrumb';
import ViewAttendance from './ViewAttendance';
import MUIDataTable from "mui-datatables";
import { getBaseURL } from '../../../Extras/server';
import { getSidebar } from '../../Layout/Sidebar';
import { useSelector } from 'react-redux';

function Attendance({ history }) {
    const user                          = useSelector(state => state.authReducer.user);
    const classes                       = styles();
    const sidebar                       = user && getSidebar(user.access_level);
    const [loading, setLoading]         = useState(true);
    const [message, setMessage]         = useState('');
    const [success, setSuccess]         = useState(false);
    const [comError, setComError]       = useState(false);
    const [attendances, setAttendances] = useState(false);
    const closeExpandable               = message => {
        setSuccess(true);
        setMessage(message);
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setSuccess(false);
        }, 10);
    }
    useEffect(()                        => {
        document.title        = 'RiiVe | Attendance';
        const abortController = new AbortController();
        const signal          = abortController.signal;

        if(user) {
            if(user.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_attendance', { schoolCode: user.id }, { signal: signal })
                    .then(response => {
                        setAttendances(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else if(user.access_level.toLowerCase() === 'teacher') {
                Axios.post(getBaseURL()+'get_attendance', { schoolCode: user.id, class: user.class }, { signal: signal })
                    .then(response => {
                        setAttendances(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [user, history, loading]);
    let rowsPerPage = [];
    const visible   = useSelector(state => state.sidebarReducer.visible);
    const columns   = [
        {
            label: "Student",
            name: "student",
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
        {
            label: "Status",
            name: "status",
            options: {
                filter: true,
            }
        },
        {
            label: "Clock In Time",
            name: "clock_in_time",
            options: {
                filter: true,
            }
        },
        {
            label: "Clock Out Time",
            name: "clock_out_time",
            options: {
                filter: true,
            }
        },
        {
            label: "Pickup Code",
            name: "pickUpCode",
            options: {
                filter: true,
            }
        },
    ];
    if (attendances) {
        if (attendances.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, attendances.length];
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
        expandableRows: true,
        renderExpandableRow: (rowData, rowMeta) => <ViewAttendance
                                                        length={rowData.length}
                                                        attendance={attendances[rowMeta.dataIndex]}
                                                        access_level={user.access_level}
                                                        closeExpandable={closeExpandable} />,
        downloadOptions: { filename: 'Attendances.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Records Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Attendances",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Attendances",
            }
        }
    };
    return (
        <>
            { success   && <Toastrr message={message} type="success" />}
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page={user && user.access_level.toLowerCase() === 'school' ? "Manage Attendances" : "Attendances"} />
                {
                    loading ? <Loader /> :
                        (attendances && attendances.length)
                            ?
                            <MUIDataTable
                                data={attendances}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Attendance" plural="Attendances" />
                }
            </main>
            <Footer />
        </>
    );
}

export default Attendance;
