<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Prijzen</template>
    </LayoutPageHeading>
    <p>This is the prices page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});
</script>
