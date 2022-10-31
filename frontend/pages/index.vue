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
  layout: 'dashboard',
  middleware: ['auth'],
  meta: {
    authLevel: 'user',
  },
});
</script>
