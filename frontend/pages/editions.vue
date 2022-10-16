<template><p>Edition page</p></template>
<script setup lang="ts">
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  authLevel: "admin",
});
</script>
