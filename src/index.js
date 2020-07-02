import React from 'react';
import App from './App';
import ReactDOM from 'react-dom';
import CircularProgress from '@material-ui/core/CircularProgress';
import { persistor, store } from './Store/Store';
import { Provider } from 'react-redux';
import { PersistGate } from 'redux-persist/lib/integration/react';
import { BrowserRouter } from 'react-router-dom';
import * as serviceWorker from './serviceWorker';
import './index.css';

ReactDOM.render(
    // <React.StrictMode>
        <BrowserRouter>
            <Provider store={store}>
                <PersistGate loading={<div className="loading-div"><CircularProgress color="secondary" /></div>} persistor={persistor}>
                    <App />
                </PersistGate>
            </Provider>
        </BrowserRouter>,
    // </React.StrictMode>,
    document.getElementById('root')
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
