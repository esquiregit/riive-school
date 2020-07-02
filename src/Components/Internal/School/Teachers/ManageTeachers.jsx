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
import AddTeacher from './AddTeacher';
import Breadcrumb from '../../Layout/Breadcrumb';
import ViewTeacher from './ViewTeacher';
import MUIDataTable from "mui-datatables";
import SchoolSidebar from '../../Layout/SidebarSchool';
import PersonAddOutlinedIcon from '@material-ui/icons/PersonAddOutlined';
import { getBack } from '../../../Extras/GoBack';
import { getBaseURL } from '../../../Extras/server';
import { storeTeachers } from '../../../../Store/Actions/TeachersActions';
import { useSelector, useDispatch } from 'react-redux';

function ManageTeachers({ history }) {//console.log('manage teachers render')
    const school                    = useSelector(state => state.authReducer.user);
    const [loading, setLoading]     = useState(true);
    const [message, setMessage]     = useState('');
    const [success, setSuccess]     = useState(false);
    const [comError, setComError]   = useState(false);
    const [showModal, setShowModal] = useState(false);
    const dispatch                  = useDispatch();
    const closeModal                = () => { setShowModal(false) }
    const closeExpandable           = message => {
        setMessage(message);
        message && setSuccess(true);
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setSuccess(false);
        }, 10);
    }
    useEffect(()                    => {
        document.title        = 'RiiVe | Manage Teachers';
        const abortController = new AbortController();
        const signal          = abortController.signal;
    
        if(school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_teachers', { school_id: school.id }, { signal: signal })
                    .then(response => {
                        dispatch(storeTeachers(response.data));
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else {
                getBack(history);
                // history.goBack();
            }
        } else {
            history.push('/');
        }

        return () => abortController.abort();
    }, [dispatch, school, history, loading]);
    const teachers  = useSelector(state => state.teachersReducer.teachers);
    let rowsPerPage = [];
    const classes   = styles();
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
            label: "Email Address",
            name: "email",
            options: {
                filter: true,
            }
        },
        {
            label: "Contact Number",
            name: "contact",
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
    ];
    if (teachers) {
        if (teachers.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, teachers.length];
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
        renderExpandableRow: (rowData, rowMeta) => <ViewTeacher length={rowData.length} teacher={teachers[rowMeta.dataIndex]} closeExpandable={closeExpandable} />,
        downloadOptions: { filename: 'Teachers.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Teachers Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Teachers",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Teachers",
            }
        }
    };
    
    return (
        <>
            { showModal && <AddTeacher closeModal={closeModal} closeExpandable={closeExpandable} /> }
            { success   && <Toastrr message={message} type="success" />}
            <Header user={school} />
            <SchoolSidebar />
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Manage Teachers" />
                {
                    loading ? <Loader /> :
                        (teachers && teachers.length)
                            ?
                            <MUIDataTable
                                data={teachers}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Teacher" plural="Teachers" />
                }
                {
                    !comError && <Fab
                        variant="extended"
                        size="medium"
                        aria-label="add"
                        className="success"
                        onClick={() => setShowModal(true)}>
                        <PersonAddOutlinedIcon className="white" />
                        <span className="ml-10">Add Teacher</span>
                    </Fab>
                }
            </main>
            <Footer />
        </>
    );
}

export default ManageTeachers;
