<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Beursoverzicht</template>
    </LayoutPageHeading>
    <p>This is the admin dashboard page</p>
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
