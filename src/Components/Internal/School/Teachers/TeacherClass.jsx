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
import Breadcrumb from '../../Layout/Breadcrumb';
import MUIDataTable from "mui-datatables";
import SchoolSidebar from '../../Layout/SidebarSchool';
import EditTeacherClass from './EditTeacherClass';
import AssignTeacherClass from './AssignTeacherClass';
import AssignmentIndOutlinedIcon from '@material-ui/icons/AssignmentIndOutlined';
import { getBack } from '../../../Extras/GoBack';
import { getBaseURL } from '../../../Extras/server';
import { useSelector } from 'react-redux';

function TeacherClass({ history }) {
    const school                          = useSelector(state => state.authReducer.user);
    const [loading, setLoading]           = useState(true);
    const [success, setSuccess]           = useState(false);
    const [comError, setComError]         = useState(false);
    const [showModal, setShowModal]       = useState(false);
    const [successMsg, setSuccessMsg]     = useState(false);
    const [teacherClass, setTeacherClass] = useState(true);
    const closeModal                      = (action, message) => {
        setShowModal(false);
        if(action && action.toLowerCase() === 'reload') {
            setSuccess(true);
            setLoading(true);
            setSuccessMsg(message);
            setTimeout(() => {
                setLoading(false);
                setSuccess(false);
            }, 10);
        }
    }
    const setInnerSuccess                 = (status, message) => {
        setSuccess(status);
        setSuccessMsg(message)
    }
    useEffect(() => {
        document.title        = 'RiiVe | Assign Teacher To Class';
        const abortController = new AbortController();
        const signal          = abortController.signal;

        if (school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL() + 'get_teacher_class_assignment', { school_id: school.id }, { signal: signal })
                    .then(response => {
                        setTeacherClass(response.data);
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
    }, [school, history, success, comError, loading]);
    let rowsPerPage = [];
    const classes = styles();
    const visible = useSelector(state => state.sidebarReducer.visible);
    const columns = [
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
        // {
        //     name: "Action",
        //     options: {
        //         filter: false,
        //         sort: false,
        //         empty: true,
        //         customBodyRender: (value, tableMeta, updateValue) => {
        //             return (
        //                 <Button
        //                     variant="contained"
        //                     color="primary"
        //                     onClick={() => {
        //                         console.log('value', value)
        //                         console.log('tableMeta', tableMeta)
        //                         console.log('updateValue', updateValue)
        //                     }}>
        //                     Edit
        //                 </Button>
        //             );
        //         }
        //     }
        // },
    ];
    if (teacherClass) {
        if (teacherClass.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, teacherClass.length];
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
        renderExpandableRow: (rowData, rowMeta) => <EditTeacherClass
            setInnerSuccess={setInnerSuccess}
            schoolId={school.id}
            length={rowData.length}
            assignmentData={teacherClass[rowMeta.dataIndex]} />,
        downloadOptions: { filename: 'TeacherClassAssignments.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Teacher/Class Assignments Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Teacher/Class Assignments",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Teachers/Class Assignments",
            }
        }
    };
    
    return (
        <>
            { success   && <Toastrr message={successMsg} type="success" /> }
            { showModal && <AssignTeacherClass closeModal={closeModal}  /> }
            <Header user={school} />
            <SchoolSidebar />
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Assign Teacher To Class" />
                {
                    loading ? <Loader /> :
                        (teacherClass && teacherClass.length)
                            ?
                            <MUIDataTable
                                data={teacherClass}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Assign Teacher" plural="Teacher/Class Assignments" />
                }
                {
                    !comError && <Fab
                        variant="extended"
                        size="medium"
                        aria-label="add"
                        className="success"
                        onClick={() => setShowModal(true)}>
                        <AssignmentIndOutlinedIcon className="white" />
                        <span className="ml-10">assign Teacher</span>
                    </Fab>
                }
            </main>
            <Footer />
        </>
    );
}

export default TeacherClass;
