<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Verkopen</template>
    </LayoutPageHeading>
    <p>This is the sell page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth'],
  meta: {
    authLevel: 'employee',
  },
});
</script>
