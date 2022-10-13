export default defineEventHandler((event) => {
    console.log(event);

    const res = $fetch("http://localhost:8000/api/auth/login", {
        method: "POST",
        body: JSON.stringify(event.data),
    })
    return {
        api: 'works',
        res
    }
})