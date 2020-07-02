import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../../Extras/styles';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import EmptyData from '../../../Extras/EmptyData';
import ViewParent from './ViewParent';
import Breadcrumb from '../../Layout/Breadcrumb';
import MUIDataTable from "mui-datatables";
import { getBaseURL } from '../../../Extras/server';
import { getSidebar } from '../../Layout/Sidebar';
import { useSelector, useDispatch } from 'react-redux';

function Parents({ history }) {
    const classes                 = styles();
    const user                    = useSelector(state => state.authReducer.user);
    const [loading, setLoading]   = useState(true);
    const [parents, setParents]   = useState(false);
    const [comError, setComError] = useState(false);
    const dispatch                = useDispatch();
    const sidebar                 = user && getSidebar(user.access_level);
    useEffect(()                  => {
        document.title        = 'RiiVe | Parents';
        const abortController = new AbortController();
        const signal          = abortController.signal;

        if(user) {
            if(user.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_parents', { school_id: user.id }, { signal: signal })
                    .then(response => {
                        setParents(response.data);
                        setLoading(false);
                    })
                    .catch(error => {
                        setComError(true);
                        setLoading(false);
                    });
            } else if(user.access_level.toLowerCase() === 'teacher') {
                Axios.post(getBaseURL()+'get_parents', { school_id: user.id, class: user.class }, { signal: signal })
                    .then(response => {
                        setParents(response.data);
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
    }, [dispatch, user, history, loading]);
    let rowsPerPage = [];
    const visible   = useSelector(state => state.sidebarReducer.visible);
    const columns   = [
        {
            label: "Parent",
            name: "parent",
            options: {
                filter: true,
            }
        },
        {
            label: "Student",
            name: "student",
            options: {
                filter: true,
            }
        },
        {
            label: "Relation",
            name: "relation",
            options: {
                filter: true,
            }
        },
        {
            label: "Phone Number",
            name: "phone",
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
        }
    ];
    if (parents) {
        if (parents.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, parents.length];
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
        expandableRows: user.access_level.toLowerCase() === 'school' ? true : false,
        renderExpandableRow: (rowData, rowMeta) => <ViewParent length={rowData.length} parent={parents[rowMeta.dataIndex]} />,
        downloadOptions: { filename: 'Parents.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Parents Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Parents",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Parents",
            }
        }
    };
    return (
        <>
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="View Parents" />
                {
                    loading ? <Loader /> :
                        (parents && parents.length)
                            ?
                            <MUIDataTable
                                data={parents}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Parent" plural="Parents" />
                }
            </main>
            <Footer />
        </>
    );
}

export default Parents;
