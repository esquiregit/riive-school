import React, { useEffect, useState } from 'react';
import Fab from '@material-ui/core/Fab';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../../Extras/styles';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import Toastrr from '../../../Extras/Toastrr';
import EmptyData from '../../../Extras/EmptyData';
import AddStudent from './AddStudent';
import Breadcrumb from '../../Layout/Breadcrumb';
import ViewStudent from './ViewStudent';
import MUIDataTable from "mui-datatables";
import NoClassAssigned from '../../../Extras/NoClassAssigned';
import PersonAddOutlinedIcon from '@material-ui/icons/PersonAddOutlined';
import { getBaseURL } from '../../../Extras/server';
import { getSidebar } from '../../Layout/Sidebar';
import { storeStudents } from '../../../../Store/Actions/StudentsActions';
import { useSelector, useDispatch } from 'react-redux';

function ManageStudents({ history }) {
    const user    = useSelector(state => state.authReducer.user);
    const classes = styles();
    const sidebar = user && getSidebar(user.access_level);

    const [loading, setLoading]               = useState(true);
    const [message, setMessage]               = useState('');
    const [success, setSuccess]               = useState(false);
    const [comError, setComError]             = useState(false);
    const [showModal, setShowModal]           = useState(false);
    const [noTeacherClass, setNoTeacherClass] = useState(false);
    
    const dispatch                  = useDispatch();
    const closeModal                = () => { setShowModal(false); }
    const closeExpandable           = message => {
        closeModal();
        setMessage(message);
        setSuccess(true);
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setSuccess(false);
        }, 1000);
    }
    useEffect(()                    => {
        document.title        = 'RiiVe | Manage Students';
        const abortController = new AbortController();
        const signal          = abortController.signal;
        if(user) {
            if(user.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_students', { school_id: user.id }, { signal: signal })
                    .then(response => {
                        dispatch(storeStudents(response.data));
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else if(user.access_level.toLowerCase() === 'teacher') {
                if(user.class) {
                    Axios.post(getBaseURL()+'get_students', { school_id: user.id, class: user.class }, { signal: signal })
                        .then(response => {
                            dispatch(storeStudents(response.data));
                            setLoading(false);
                        })
                        .catch(error => {
                            setComError(true);
                            setLoading(false);
                        });
                } else {
                    setLoading(false);
                    setNoTeacherClass(true);
                }
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [dispatch, user, history, loading]);
    
    const students  = useSelector(state => state.studentsReducer.students);
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
            label: "Class",
            name: "class",
            options: {
                filter: true,
            }
        },
        {
            label: "Gender",
            name: "gender",
            options: {
                filter: true,
            }
        },
        {
            label: "Student Code",
            name: "studentCode",
            options: {
                filter: true,
            }
        },
    ];
    if (students) {
        if (students.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, students.length];
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
        renderExpandableRow: (rowData, rowMeta) => <ViewStudent length={rowData.length} student={students[rowMeta.dataIndex]} access_level={user.access_level} closeExpandable={closeExpandable} />,
        downloadOptions: { filename: 'Students.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Students Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Students",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Students",
            }
        }
    };
    return (
        <>
            { showModal && <AddStudent closeModal={closeModal} closeExpandable={closeExpandable} /> }
            { success   && <Toastrr message={message} type="success" />}
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page={user && user.access_level.toLowerCase() === 'school' ? "Manage Students" : "Students"} />
                {
                    loading ? <Loader /> :
                        (students && students.length)
                            ?
                            <MUIDataTable
                                data={students}
                                columns={columns}
                                options={options} />
                            : 
                            noTeacherClass ?
                            <NoClassAssigned type="Students" />
                            :
                            <EmptyData error={comError} single="Student" plural="Students" />
                }
                {
                    !comError && !noTeacherClass && <Fab
                        variant="extended"
                        size="medium"
                        aria-label="add"
                        className="success"
                        onClick={() => setShowModal(true)}>
                        <PersonAddOutlinedIcon className="white" />
                        <span className="ml-10">Add Student</span>
                    </Fab>
                }
            </main>
            <Footer />
        </>
    );
}

export default ManageStudents;
