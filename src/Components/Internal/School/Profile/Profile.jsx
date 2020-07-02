import React from 'react';
import clsx from 'clsx';
import Footer from '../../Layout/Footer';
import Header from '../../Layout/Header';
import styles from '../../../Extras/styles';
import Breadcrumb from '../../Layout/Breadcrumb';
import ProfileSchool from './ProfileSchool';
import ProfileTeacher from './ProfileTeacher';
import { getSidebar } from '../../Layout/Sidebar';
import { useSelector } from 'react-redux';

function Profile({ history }) {
    const user    = useSelector(state => state.authReducer.user);
    const classes = styles();
    const content = user && user.access_level.toLowerCase() === 'school' ? <ProfileSchool school={user} /> : <ProfileTeacher teacher={user} />;
    const sidebar = user && getSidebar(user.access_level);
    const visible = useSelector(state => state.sidebarReducer.visible);

    React.useEffect(() => {
        document.title = 'RiiVe | Profile';
    }, [user, history]);
    
    return (
        <>
            <Header user={user} />
            {sidebar}
            <main
                className={clsx(classes.contentMedium, {
                    [classes.contentWide]: !visible,
                })}>
                <Breadcrumb page="Profile" />
                {content}
            </main>
            <Footer />
        </>
    )
}

export default Profile;
