import React from 'react';
import Button from '@material-ui/core/Button';
import Dialog from '@material-ui/core/Dialog';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogContentText from '@material-ui/core/DialogContentText';
import DialogTitle from '@material-ui/core/DialogTitle';
import Slide from '@material-ui/core/Slide';

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

export default function AlertDialogSlide({ message, closeModal }) {
    const [open, setOpen] = React.useState(true);
    const handleClose     = result => {
        setOpen(false);
        closeModal(null, null, result);
    };

    return (
        <div>
            <Dialog
                open={open}
                keepMounted
                onClose={handleClose}
                disableBackdropClick={true}
                disableEscapeKeyDown={true}
                TransitionComponent={Transition}
                aria-labelledby="alert-dialog-slide-title"
                aria-describedby="alert-dialog-slide-description" >
                <DialogTitle id="alert-dialog-slide-title">Confirm Action</DialogTitle>
                <DialogContent>
                    <DialogContentText id="alert-dialog-slide-description">
                        {message}
                    </DialogContentText>
                </DialogContent>
                <DialogActions>
                    <Button onClick={() => handleClose('No')} color="primary">
                        No
                    </Button>
                    <Button onClick={() => handleClose('Yes')} color="secondary">
                        Yes
                    </Button>
                </DialogActions>
            </Dialog>
        </div>
    );
}