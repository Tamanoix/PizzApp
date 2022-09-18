
export const sortData = (data: any , value: any) => {
    const sortedData = data.sort((a: any, b: any) => a[value] < b[value] ? -1 : 1);
    return sortedData; 
}
