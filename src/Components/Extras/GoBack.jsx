export const getBack = history => {
    if(history) {
        history.goBack();
    }

    history.push('/dashboard/');
}