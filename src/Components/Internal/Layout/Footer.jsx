import React from 'react';
import clsx from 'clsx';
import styles from '../../Extras/styles';
import { useSelector } from 'react-redux';

const Footer = () => {
    const date        = new Date();
    const startYear   = 2020;
    const currentYear = date.getFullYear();
    const visible     = useSelector(state => state.sidebarReducer.visible);
    const classes     = styles();
    return (
        <footer
            className={clsx(classes.contentMedium, {
                [classes.contentWide]: !visible,
            })}> 
            © RiiVe &bull;&nbsp;
            { startYear === currentYear
            ?
            currentYear
            :
            startYear+' - '+currentYear
            }
            &nbsp;&bull; All Rights Reserved.
        </footer>
    );
}

export default Footer;
