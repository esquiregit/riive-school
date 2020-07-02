const initialState = {
    students : null
};
const studentsReducer  = (state = initialState, action) => {
    switch(action.type) {
        case 'STORE_STUDENTS':
            return {
                ...state,
                students : action.payload
            };
        default:
            return state;
    }
}

export default studentsReducer;
