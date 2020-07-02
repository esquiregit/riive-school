import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Axios from 'axios';
import styles from '../../../Extras/styles';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import EmptyData from '../../../Extras/EmptyData';
import ViewVisitor from './ViewVisitor';
import Breadcrumb from '../../Layout/Breadcrumb';
import MUIDataTable from "mui-datatables";
import SidebarSchool from '../../Layout/SidebarSchool';
import { getBack } from '../../../Extras/GoBack';
import { getBaseURL } from '../../../Extras/server';
import { useSelector } from 'react-redux';

function Visitors({ history }) {
    const classes                 = styles();
    const school                  = useSelector(state => state.authReducer.user);
    const [loading, setLoading]   = useState(true);
    const [visitors, setVisitors] = useState(false);
    const [comError, setComError] = useState(false);
    useEffect(()                  => {
        document.title        = 'RiiVe | Visitors';
        const abortController = new AbortController();
        const signal          = abortController.signal;

        if(school) {
            if(school.access_level.toLowerCase() === 'school') {
                Axios.post(getBaseURL()+'get_visitors', { schoolID: school.id }, { signal: signal })
                    .then(response => {
                        setVisitors(response.data);
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
            label: "Visitor",
            name: "visitorName",
            options: {
                filter: true,
            }
        },
        {
            label: "Person To Visit",
            name: "personToVisit",
            options: {
                filter: true,
            }
        },
        {
            label: "Clock In Time",
            name: "clockInTime",
            options: {
                filter: true,
            }
        },
        {
            label: "Clock Out Time",
            name: "clockOutTime",
            options: {
                filter: true,
            }
        },
        {
            label: "Purpose Of Visit",
            name: "purposeOfVisit",
            options: {
                filter: true,
            }
        }
    ];
    if (visitors) {
        if (visitors.length < 100) {
            rowsPerPage = [10, 25, 50, 100];
        } else {
            rowsPerPage = [10, 25, 50, 100, visitors.length];
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
        renderExpandableRow: (rowData, rowMeta) => <ViewVisitor length={rowData.length} visitor={visitors[rowMeta.dataIndex]} />,
        downloadOptions: { filename: 'Visitors.csv', separator: ', ' },
        page: 0,
        selectableRows: 'none',
        textLabels: {
            body: {
                noMatch: "No Matching Visitors Found. Change Keywords and Try Again....",
                columnHeaderTooltip: column => `Sort By ${column.label}`
            },
            toolbar: {
                search: "Search Visitors",
                viewColumns: "Show/Hide Columns",
                filterTable: "Filter Visitors",
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
                <Breadcrumb page="Visitors" />
                {
                    loading ? <Loader /> :
                        (visitors && visitors.length)
                            ?
                            <MUIDataTable
                                data={visitors}
                                columns={columns}
                                options={options} />
                            : <EmptyData error={comError} single="Visitor" plural="Visitors" />
                }
            </main>
            <Footer />
        </>
    );
}

export default Visitors;
