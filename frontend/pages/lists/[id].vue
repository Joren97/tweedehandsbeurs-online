<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>List</template>
    </LayoutPageHeading>
    <p>This is the list page</p>
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
