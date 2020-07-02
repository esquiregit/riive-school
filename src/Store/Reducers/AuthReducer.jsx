const initialState = {
    message    : null,
    user       : null,
    isLoggedIn : false
};
const authReducer  = (state = initialState, action) => {
    switch(action.type) {
        case 'LOG_IN':
            return {
                ...state,
                message    : 'Login Success',
                user       : action.payload,
                isLoggedIn : true
            };
        case 'IS_LOGGED_IN':
            return state.user ? true : false;
        case 'UPDATE':
            return {
                ...state,
                user: action.payload
            };
        default: return state;
    }
}

export default authReducer;
