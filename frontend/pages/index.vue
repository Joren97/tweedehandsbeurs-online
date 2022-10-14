<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Dashboard</template>
    </LayoutPageHeading>
    <p>This is the index page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "admin",
  },
});
</script>
