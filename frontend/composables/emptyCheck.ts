export const emptyCheck = (value: any) => {
    if (value === undefined || value === null || value === "") {
        return "-";
    }

    return value;
};
