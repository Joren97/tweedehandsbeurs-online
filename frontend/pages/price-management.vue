<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Prijzenbeheer</template>
    </LayoutPageHeading>
    <p>This is the price management page</p>
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
