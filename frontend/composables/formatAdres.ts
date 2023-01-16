export const formatAdress = (user: any) => {
    const address = user.address;
    const postalCode = user.postalCode;
    const city = user.city;

    // If all three are empty, return a dash
    if (!address && !postalCode && !city) return "-";
    // If postalCode and city is empty, return address
    if (!postalCode && !city) return address;
    // If address is empty, return postalCode and city
    if (!address) return `${postalCode} ${city}`;
    // Return address, postalCode and city
    return `${user.address}, ${user.postalCode} ${user.city}`;
};