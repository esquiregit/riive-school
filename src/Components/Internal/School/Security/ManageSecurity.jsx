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
import AddSecurity from './AddSecurity';
import Breadcrumb from '../../Layout/Breadcrumb';
import ViewSecurity from './ViewSecurity';
import MUIDataTable from "mui-datatables";
import SidebarSchool from '../../Layout/SidebarSchool';
import PersonAddOutlinedIcon from '@material-ui/icons/PersonAddOutlined';
import { getBack } from '../../../Extras/GoBack';
import { getBaseURL } from '../../../Extras/server';
import { useSelector } from 'react-redux';

function ManageSecurity({ history }) {
    const classes = styles();
    const school  = useSelector(state => state.authReducer.user);
    const visible = useSelector(state => state.sidebarReducer.visible);

    const [loading, setLoading]       = useState(true);
    const [message, setMessage]       = useState('');
    const [success, setSuccess]       = useState(false);
    const [comError, setComError]     = useState(false);
    const [showModal, setShowModal]   = useState(false);
    const [securities, setSecurities] = useState(false);

    const closeModal      = () => { setShowModal(false); }
    const closeExpandable = message => {
        setMessage(message);
        setSuccess(true);
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setSuccess(false);
        }, 10);
    }
    useEffect(()          => {
        document.title        = 'RiiVe | Manage Securities';
        const abortController = new AbortController();
        const signal          = abortController.signal;
        
        if(school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_securities', { schoolCode: school.id }, { signal: signal })
                    .then(response => {
                        setSecurities(response.data);
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
    const columns   = [
        {
            label: "Name",
            name: "name",
            options: {
                filter: true,
            }
        },
        {
            label: "Contact",
            name: "contact",
            options: {
                filter: true,
            }
        },
        {
            label: "Account Type",
            name: "accountType",
            options: {
                filter: true,
            }
        },
    ];
    if (securities) {
        if (securities.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, securities.length];
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
        renderExpandableRow: (rowData, rowMeta) => <ViewSecurity length={rowData.length} security={securities[rowMeta.dataIndex]} closeExpandable={closeExpandable} />,
        downloadOptions: { filename: 'Security Personnel.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Security Personnel Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Security Personnel",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Security Personnel",
            }
        }
    };
    return (
        <>
            { showModal && <AddSecurity closeModal={closeModal} closeExpandable={closeExpandable} /> }
            { success   && <Toastrr message={message} type="success" />}
            <Header user={school} />
            <SidebarSchool />
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Manage Securities" />
                {
                    loading ? <Loader /> :
                        (securities && securities.length)
                            ?
                            <MUIDataTable
                                data={securities}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Security" plural="Securities" />
                }
                {
                    !comError && <Fab
                        variant="extended"
                        size="medium"
                        aria-label="add"
                        className="success"
                        onClick={() => setShowModal(true)}>
                        <PersonAddOutlinedIcon className="white" />
                        <span className="ml-10">Add Security</span>
                    </Fab>
                }
            </main>
            <Footer />
        </>
    );
}

export default ManageSecurity;
