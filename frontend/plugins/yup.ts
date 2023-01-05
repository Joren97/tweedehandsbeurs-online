
import { setLocale } from "yup";

export default defineNuxtPlugin((nuxtApp) => {
    setLocale({
        mixed: {
            required: ({ value, label, path }) => {
                if (label) {
                    return `${label} is een verplicht veld.`;
                } else {
                    return `Dit is een verplicht veld.`;
                }
            },
        },
        string: {
            email: "Dit is geen geldig emailadres",
        },
    });
});
