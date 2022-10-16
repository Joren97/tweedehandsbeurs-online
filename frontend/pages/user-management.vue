<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Gebruikers</template>
    </LayoutPageHeading>
    <p>This is the user management page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});
</script>
