export const emptyCheck = (value: any) => {
    console.log("emptyCheck");


    if (value === undefined || value === null || value === "") {
        return "-";
    }

    return value;
};
