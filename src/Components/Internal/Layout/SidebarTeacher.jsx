import React from 'react';
import clsx from 'clsx';
import List from '@material-ui/core/List';
import Drawer from '@material-ui/core/Drawer';
import Divider from '@material-ui/core/Divider';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import SendOutlinedIcon from '@material-ui/icons/SendOutlined';
import EmailOutlinedIcon from '@material-ui/icons/EmailOutlined';
import HowToRegOutlinedIcon from '@material-ui/icons/HowToRegOutlined';
import ScheduleOutlinedIcon from '@material-ui/icons/ScheduleOutlined';
import DashboardOutlinedIcon from '@material-ui/icons/DashboardOutlined';
import AssessmentOutlinedIcon from '@material-ui/icons/AssessmentOutlined';
import LocalLibraryOutlinedIcon from '@material-ui/icons/LocalLibraryOutlined';
import DirectionsCarOutlinedIcon from '@material-ui/icons/DirectionsCarOutlined';
import EventAvailableOutlinedIcon from '@material-ui/icons/EventAvailableOutlined';
import { NavLink } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import { useSelector } from 'react-redux';

const drawerWidth = 250;
const useStyles   = makeStyles((theme) => ({
    drawer: {
        width: drawerWidth,
        flexShrink: 0,
        whiteSpace: 'nowrap',
    },
    drawerOpen: {
        width: drawerWidth,
        transition: theme.transitions.create('width', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.enteringScreen,
        }),
    },
    drawerClose: {
        transition: theme.transitions.create('width', {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        overflowX: 'hidden',
        width: theme.spacing(7) + 1,
        [theme.breakpoints.up('sm')]: {
            width: theme.spacing(9) - 5,
        },
    },
}));
const menuItems = [
    {
        label: 'Dashboard',
        icon : <DashboardOutlinedIcon />,
        url  : '/dashboard/'
    },
    {
        type: 'divider',
        key : 1
    },
    {
        label: 'Students',
        icon : <LocalLibraryOutlinedIcon />,
        url  : '/manage-students/'
    },
    {
        type: 'divider',
        key : 2
    },
    {
        label: 'Parents',
        icon : <HowToRegOutlinedIcon />,
        url  : '/parents/'
    },
    {
        type: 'divider',
        key : 3
    },
    {
        label: 'Pickups',
        icon : <DirectionsCarOutlinedIcon />,
        url  : '/pickups/'
    },
    {
        type: 'divider',
        key : 4
    },
    {
        label: 'Mark Attendance',
        icon : <ScheduleOutlinedIcon />,
        url  : '/mark-attendance/'
    },
    {
        label: 'Attendance',
        icon : <EventAvailableOutlinedIcon />,
        url  : '/attendance/'
    },
    {
        type: 'divider',
        key : 5
    },
    {
        label: 'Manage Assessments',
        icon : <AssessmentOutlinedIcon />,
        url  : '/manage-assessments/'
    },
    {
        type: 'divider',
        key : 6
    },
    {
        label: 'SMS',
        icon : <SendOutlinedIcon />,
        url  : '/sms/'
    },
    {
        type: 'divider',
        key : 7
    },
    {
        label: 'Email',
        icon : <EmailOutlinedIcon />,
        url  : '/email/'
    }
];

const SidebarTeacher = () => {
    const visible = useSelector(state => state.sidebarReducer.visible);
    const classes = useStyles();
    return (
        <Drawer
            variant="permanent"
            classes={{
                paper: clsx({
                    [classes.drawerOpen]: visible,
                    [classes.drawerClose]: !visible,
                }),
            }}>
            <Divider />
            <List>
                {menuItems.map((menuItem) => (
                    menuItem.type
                    ?
                    <Divider key={menuItem.key} />
                    :
                    <NavLink to={menuItem.url} key={menuItem.label}>
                        <ListItem button>
                            <ListItemIcon>
                                {menuItem.icon}
                            </ListItemIcon>
                            <ListItemText primary={menuItem.label} />
                        </ListItem>
                    </NavLink>
                ))}
            </List>
        </Drawer>
    );
}

export default SidebarTeacher;
