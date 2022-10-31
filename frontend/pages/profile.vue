<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Profile</template>
    </LayoutPageHeading>
    <p>This is the profile page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: 'dashboard',
  middleware: ['auth'],
  meta: {
    authLevel: 'user',
  },
  title: 'Profiel',
});
</script>
