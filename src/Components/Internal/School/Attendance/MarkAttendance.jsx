import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Axios from 'axios';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import styles from '../../../Extras/styles';
import Tooltip from "@material-ui/core/Tooltip";
import Toastrr from '../../../Extras/Toastrr';
import Backdrop from '@material-ui/core/Backdrop';
import EmptyData from '../../../Extras/EmptyData';
import Breadcrumb from '../../Layout/Breadcrumb';
import IconButton from "@material-ui/core/IconButton";
import MUIDataTable from "mui-datatables";
import SidebarTeacher from '../../Layout/SidebarTeacher';
import ConfirmDialogue from '../../../Extras/ConfirmDialogue';
import CircularProgress from '@material-ui/core/CircularProgress';
import DoneAllOutlinedIcon from '@material-ui/icons/DoneAllOutlined';
import { getBack } from '../../../Extras/GoBack';
import { getBaseURL } from '../../../Extras/server';
import { useSelector } from 'react-redux';

function MarkAttendance({ history }) {
    const user                    = useSelector(state => state.authReducer.user);
    const visible                 = useSelector(state => state.sidebarReducer.visible);
    let classs                    = user.class.startsWith('JHS') || user.class.startsWith('SHS') ? user.class : 'Class ' + user.class;
    const classes                 = styles();
    const [error, setError]       = useState(false);
    const [loading, setLoading]   = useState(true);
    const [message, setMessage]   = useState('');
    const [success, setSuccess]   = useState(false);
    const [comError, setComError] = useState(false);
    const [students, setStudents] = useState(false);
    const [backdrop, setBackdrop]                 = useState(false);
    const [showDialogue, setShowDialogue]         = useState(false);
    const [selectedRows, setSelectedRows]         = useState([]);
    const [selectedRowsText, setSelectedRowsText] = useState('');
    useEffect(() => {
        const date = new Date();
        
        if(date.getDay() !== 0 && date.getDay() !== 6) {
            document.title = "RiiVe | Mark " + classs + " Attendance";
            const abortController = new AbortController();
            const signal = abortController.signal;
            if (user) {
                if (user.access_level.toLowerCase() === 'teacher') {
                    Axios.post(getBaseURL() + 'get_attendance_students', { schoolCode: user.school_id, class: user.class }, { signal: signal })
                        .then(response => {
                            setStudents(response.data);
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
        } else {
            alert('No Attendance Marking On Weekends');
            getBack(history);
        }
    }, [user, classs, history, loading]);
    const columns        = [
        {
            label: "Student",
            name: "student",
            options: {
                filter: true,
            }
        },
    ];
    const options        = {
        print: false,
        filter: false,
        download: false,
        viewColumns: false,
        responsive: 'standard',
        pagination: false,
        resizableColumns: false,
        page: 0,
        selectableRows: 'multiple',
        selectableRowsOnClick: true,
        onRowSelectionChange: (currentRowsSelected, rowsSelected) => {
            if(rowsSelected.length === 1) {
                setSelectedRowsText(" Student Selected");
            } else {
                setSelectedRowsText(" Students Selected");
            }
        },
        customToolbarSelect: selectedRows => (
            <Tooltip title="Mark Attendance For Selected Students">
                <IconButton
                    // onClick={() => markAttendance(selectedRows)}
                    onClick={() => {
                        setShowDialogue(true);
                        setSelectedRows(selectedRows);
                    }}
                    style={{
                        marginRight: "24px",
                        display: "block",
                    }}>
                    <DoneAllOutlinedIcon />
                </IconButton>
            </Tooltip>
        ),
        textLabels: {
            body: {
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            selectedRows: {
                text: selectedRowsText,
                delete: "Mark Attendance For Selected Students",
                deleteAria: "Mark Attendance For Selected Students",
            },
        }
    };
    const closeModal     = (action, message, result) => {
        setShowDialogue(false);
        
        if(result && result.toLowerCase() === 'yes') {
            markAttendance(selectedRows);
        }
    };
    const markAttendance = selectedRows => {
        setError(false);
        setSuccess(false);
        setComError(false);
        setBackdrop(true);
        const abortController = new AbortController();
        const signal          = abortController.signal;
        const newArr          = selectedRows.data.map(student => students[student.dataIndex].studentid);

        Axios.post(getBaseURL()+'mark_attendance', { students_array: newArr, schoolCode: user.school_id }, { signal: signal })
            .then(response => {
                if(response.data[0].status.toLowerCase() === 'success') {
                    setError(false);
                    setLoading(true);
                    setSuccess(true);
                    setMessage(response.data[0].message);
                    // setTimeout(() => history.push('/attendance/'), 2000);
                    // closeModal('reload', response.data[0].message, null);
                } else {
                    setError(true);
                    setSuccess(false);
                    setMessage(response.data[0].message);
                }
                setBackdrop(false);
            })
            .catch(error => {
                setComError(true);
                setBackdrop(false);
                setMessage('Network Error. Server Unreachable....');
            });

        return () => abortController.abort();
    };

    return (
        <>
            { error        && <Toastrr         message={message} type="error" />}
            { success      && <Toastrr         message={message} type="success" />}
            { comError     && <Toastrr         message={message} type="info" />}
            { showDialogue && <ConfirmDialogue message={'Are You Sure You Want To Mark Attendance?'} closeModal={closeModal} /> }
            <Backdrop className={classes.backdrop} open={backdrop}>
                <CircularProgress color="inherit" /> <span className='ml-15'>Marking Attendance....</span>
            </Backdrop>
            <Header user={user} />
            <SidebarTeacher />
            <main
                id="attendance-main"
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page={"Mark " + classs + " Attendance"} />
                {
                    loading ? <Loader /> :
                        (students && students.length)
                            ?
                            <MUIDataTable
                                data={students}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Student" plural="Attendance" />
                }
            </main>
            <Footer />
        </>
    );
}

export default MarkAttendance;
