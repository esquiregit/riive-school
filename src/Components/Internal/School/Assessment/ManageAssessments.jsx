import React, { useEffect, useState } from 'react';
import Fab from '@material-ui/core/Fab';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../../Extras/styles';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import Toastrr from '../../../Extras/Toastrr';
import AddAssessment from './AddAssessment';
import Breadcrumb from '../../Layout/Breadcrumb';
import MUIDataTable from "mui-datatables";
import ViewAssessment from './ViewAssessment';
import AddOutlinedIcon from '@material-ui/icons/AddOutlined';
import NoClassAssigned from '../../../Extras/NoClassAssigned';
import ReportOffOutlinedIcon from '@material-ui/icons/ReportOffOutlined';
import { getBaseURL } from '../../../Extras/server';
import { getSidebar } from '../../Layout/Sidebar';
import { useSelector } from 'react-redux';

function ManageAssessments({ history }) {
    const user    = useSelector(state => state.authReducer.user);
    const classes = styles();

    const [loading, setLoading]               = useState(true);
    const [message, setMessage]               = useState('');
    const [success, setSuccess]               = useState(false);
    const [comError, setComError]             = useState(false);
    const [showModal, setShowModal]           = useState(false);
    const [assessments, setAssessments]       = useState([]);
    const [noTeacherClass, setNoTeacherClass] = useState(false);

    const closeModal      = (action) => { 
        action && action.toLowerCase() === 'close' && setShowModal(false);
    }
    const closeExpandable = message => {
        setMessage(message);
        setSuccess(true);
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setSuccess(false);
        }, 10);
    }
    const sidebar         = user && getSidebar(user.access_level);
    useEffect(()          => {
        document.title        = 'RiiVe | Manage Assessments';
        const abortController = new AbortController();
        const signal          = abortController.signal;
        if(user) {
            if(user.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_assessments', { School_id: user.id }, { signal: signal })
                    .then(response => {
                        setAssessments(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else if(user.access_level.toLowerCase() === 'teacher') {
                if(user.class) {
                    Axios.post(getBaseURL()+'get_assessments', { School_id: user.id, class: user.class }, { signal: signal })
                        .then(response => {
                            setAssessments(response.data);
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
            label: "Class",
            name: "class",
            options: {
                filter: true,
            }
        },
        {
            label: "Term",
            name: "term",
            options: {
                filter: true,
            }
        },
        {
            label: "Academic Year",
            name: "academic_year",
            options: {
                filter: true,
            }
        },
        {
            label: "Subject",
            name: "subject",
            options: {
                filter: true,
            }
        },
        {
            label: "Score",
            name: "total_score",
            options: {
                filter: true,
            }
        },
    ];
    if (assessments) {
        if (assessments.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, assessments.length];
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
        renderExpandableRow: (rowData, rowMeta) => <ViewAssessment 
            length={rowData.length} 
            assessment={assessments[rowMeta.dataIndex]} 
            access_level={user.access_level}
            school_id={user.school_id}
            teacher_id={user.id}
            closeExpandable={closeExpandable} />,
        downloadOptions: { filename: 'Assessments.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Assessments Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Assessments",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Assessments",
            }
        }
    };
    return (
        <>
            { showModal && <AddAssessment closeModal={closeModal} closeExpandable={closeExpandable} /> }
            { success   && <Toastrr message={message} type="success" />}
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Manage Assessments" />
                {
                    loading ? <Loader /> :
                        (assessments && assessments.length)
                            ?
                            <MUIDataTable
                                data={assessments}
                                columns={columns}
                                options={options} />
                            : 
                            noTeacherClass ?
                            <NoClassAssigned type="Assessments" />
                            : <div className="empty-data">
                                <>
                                    <ReportOffOutlinedIcon />
                                    <span>
                                        <strong>No Assessments Found</strong>
                                        &nbsp;
                                        {
                                            user && user.access_level.toLowerCase() === 'teacher' && <span>click the "add Assessment" button below to add one</span>
                                        }
                                    </span>
                                </>
                            </div>
                }
                {
                    !comError && !noTeacherClass && user.access_level.toLowerCase() === 'teacher' && <Fab
                        variant="extended"
                        size="medium"
                        aria-label="add"
                        className="success"
                        onClick={() => setShowModal(true)}>
                        <AddOutlinedIcon className="white" />
                        <span className="ml-10">Add Assessment</span>
                    </Fab>
                }
            </main>
            <Footer />
        </>
    );
}

export default ManageAssessments;
