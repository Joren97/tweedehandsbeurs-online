<template><p>Sell page</p></template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  authLevel: "employee",
});
</script>
