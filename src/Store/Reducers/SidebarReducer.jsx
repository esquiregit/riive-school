const initialState = {
    visible: true
}

const sidebarReducer = (state = initialState, action) => {
    switch(action.type) {
        case 'TOGGLE_SIDEBAR':
            return {
                ...state,
                visible: !state.visible
            };
        case 'SHOW_SIDEBAR':
            return {
                ...state,
                visible: true
            };
        case 'HIDE_SIDEBAR':
            return {
                ...state,
                visible: false
            };
        default:
            return state;
    }
}

export default sidebarReducer;
