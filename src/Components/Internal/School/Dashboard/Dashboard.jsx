import React, { useEffect, useState } from 'react';
import clsx from 'clsx';
import Card from '@material-ui/core/Card';
import Grid from '@material-ui/core/Grid';
import Axios from 'axios';
import Chart from './Chart';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import Loader from '../../../Extras/Loadrr';
import styles from '../../../Extras/styles';
import Calendarr from './Calendar';
import ClassList from './ClassList';
import Breadcrumb from '../../Layout/Breadcrumb';
import Statistics from './Statistics';
import { getBaseURL } from '../../../Extras/server';
import { getSidebar } from '../../Layout/Sidebar';
import { useSelector } from 'react-redux';

function Dashboard({ history }) {
    const user                      = useSelector(state => state.authReducer.user);//console.log(user)
    const classes                   = styles();
    const visible                   = useSelector(state => state.sidebarReducer.visible);
    const isLoggedIn                = useSelector(state => state.authReducer.isLoggedIn);
    const [stats, setStats]         = useState([]);
    const [loading, setLoading]     = useState(true);
    const [classList, setClassList] = useState([]);

    useEffect(() => {
        document.title = 'RiiVe | Dashboard';

        if(isLoggedIn) {
            Axios.post(getBaseURL() + 'get_dashboard_stats', { School_id: user.id})
                .then(response => {
                    setStats(response.data[0]);

                    if(user.access_level.toLowerCase() === 'teacher' && user.class) {
                        Axios.post(getBaseURL()+'get_students', { school_id: user.id, class: user.class })
                            .then(res => {
                                setClassList(res.data);
                                setLoading(false);
                            })
                            .catch(error => {
                                setLoading(false);
                            });
                    }
                    setLoading(false);
                })
                .catch(error => {
                    // setComError(true);
                    setLoading(false);
                });
        } else {
            history.push('/');
        }
    }, [user, isLoggedIn, history]);
    const sidebar    = user && getSidebar(user.access_level);
    
    return (
        <>
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Dashboard" />
                {
                    loading ? <Loader /> :
                        <>
                            {
                                user && user.access_level.toLowerCase() === 'school' && <Statistics
                                                                    total_students={stats.total_students}
                                                                    total_teachers={stats.total_teachers}
                                                                    total_security={stats.total_security} />
                            }
                            <Grid container spacing={3} className="mt-20">
                                <Grid item sm={12} md={8}>
                                    { user && user.access_level.toLowerCase() === 'school' ? <Chart stats={stats} /> : <ClassList classList={classList} classs={user && user.class} /> }
                                </Grid>
                                <Grid item sm={12} md={4}>
                                    <Card variant="outlined">
                                        <Calendarr />
                                    </Card>
                                </Grid>
                            </Grid>
                        </>
                }
            </main>
            <Footer />
        </>
    )
}

export default Dashboard;
