export const getMaxDate = () => {
    const date    = new Date();
    const day     = date.getDate();
    const month   = date.getMonth() < 10 ? '0'+(1+date.getMonth()) : (1+date.getMonth());
    const year    = date.getFullYear();
    
    return year+'-'+month+'-'+day;
}

// export const todaysDate = () => {
//     const date    = new Date();
//     const day     = date.getDate();
//     const month   = date.getMonth() < 10 ? '0'+(1+date.getMonth()) : (1+date.getMonth());
//     const year    = date.getFullYear();
//     const hour    = date.getHours() < 10 ? '0'+date.getHours() : date.getHours();
//     const minutes = date.getMinutes() < 10 ? '0'+date.getMinutes() : date.getMinutes();
//     const seconds = date.getSeconds() < 10 ? '0'+date.getSeconds() : date.getSeconds();
    
//     return 
// }