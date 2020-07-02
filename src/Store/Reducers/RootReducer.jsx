import authReducer from './AuthReducer';
import sidebarReducer from './SidebarReducer';
import studentsReducer from './StudentsReducer';
import teachersReducer from './TeachersReducer';
import { combineReducers } from 'redux';

const appReducer = combineReducers({
    authReducer,
    sidebarReducer,
    studentsReducer,
    teachersReducer,
});

const rootReducer = (state, action) => {
    if(action.type === 'LOG_OUT') {
        state = undefined;
    }
    
    return appReducer(state, action);
};

export default rootReducer;
