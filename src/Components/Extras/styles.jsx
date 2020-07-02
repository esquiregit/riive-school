import { makeStyles } from '@material-ui/core/styles';

const drawerWidth = 250;
const styles      = makeStyles((theme) => ({
    root: {
        display: 'flex',
    },
    toolbar: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'flex-end',
        padding: theme.spacing(0, 1),
        // necessary for content to be below app bar
        ...theme.mixins.toolbar,
    },
    content: {
        flexGrow: 1,
        padding: theme.spacing(3),
    },
    contentMedium: {
        marginLeft: drawerWidth,
        // width: `calc(100% - ${drawerWidth}px)`,
        // width: `calc(100% - 500px)`,
        transition: theme.transitions.create(['width', 'margin'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.enteringScreen,
        }),
        flexGrow: 1,
        padding: theme.spacing(3),
    },
    contentWide: {
        marginLeft: 65,
        // width: `calc(100% - 50px)`,
        transition: theme.transitions.create(['width', 'margin'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        flexGrow: 1,
        padding: theme.spacing(3),
    },
    fullHeight: {
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    },
    fullHeightDiv: {
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100%',
        width: '100%',
    }
}));

export default styles;
