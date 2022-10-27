export const toEuro = (value: any) => {
    if (value === undefined || value === null || value === "") {
        return "";
    }

    // Replace the dot with a comma
    return "€ " + value.toString().replace(".", ",");
};
