import thunk from 'redux-thunk';
import storage from 'redux-persist/lib/storage';
import rootReducer from './Reducers/RootReducer';
import autoMergeLevel2 from 'redux-persist/lib/stateReconciler/autoMergeLevel2';
import { composeWithDevTools } from 'redux-devtools-extension';
import { createStore, applyMiddleware } from 'redux';
import { persistStore, persistReducer } from 'redux-persist';

const persistConfig = {
    key: 'root',
    storage: storage,
    blacklist: ['sidebarReducer', 'studentsReducer', 'teachersReducer'],
    stateReconciler: autoMergeLevel2
};
const rReducer = persistReducer(persistConfig, rootReducer);

const store = createStore(
    rReducer,
    composeWithDevTools(
    applyMiddleware(thunk)
    // window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
));
// store.subscribe(() => console.log(store.getState()) );
const persistor = persistStore(store);
export { persistor, store };
