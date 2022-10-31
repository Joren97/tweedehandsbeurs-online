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
  layout: 'dashboard',
  middleware: ['auth'],
  meta: {
    authLevel: 'admin',
  },
});
</script>
