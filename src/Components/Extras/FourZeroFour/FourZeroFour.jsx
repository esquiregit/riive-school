import React, { useEffect } from 'react';
import Button from '@material-ui/core/Button';
import { getBack } from '../../Extras/GoBack';
import './FourZeroFour.css';

const FourZeroFour = ({ history }) => {
    useEffect(() => { document.title = 'RiiVe | Error 404'; }, []);
    const handleClick = () => {
        getBack(history);
        // history.goBack();
    }

    return (
        <div className="error-404">
            <p className="first">OOPS! PAGE NOT FOUND</p>
            <p className="middle">404</p>
            <p className="last">The Requested URL Doesn't Exist</p>
            <Button
                variant="contained"
                color="primary"
                onClick={handleClick}
                disableElevation>
                Go Back
            </Button>
        </div>
    );
}

export default FourZeroFour;
