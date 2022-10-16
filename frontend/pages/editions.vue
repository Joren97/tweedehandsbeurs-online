<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Edities</template>
    </LayoutPageHeading>
    <p>This is the editions page</p>
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
