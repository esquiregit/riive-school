import React from 'react';
import './FormErrorMessage.css';

const FormErrorMessage = ({ message }) => {
    if(message) {
        return <span className="error-message">{message}</span>
    }
}

export default FormErrorMessage
