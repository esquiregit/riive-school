const initialState = {
    teachers : null
};
const teachersReducer  = (state = initialState, action) => {
    switch(action.type) {
        case 'STORE_TEACHERS':
            return {
                ...state,
                teachers : action.payload
            };
        default:
            return state;
    }
}

export default teachersReducer;
