<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Prijzen</template>
    </LayoutPageHeading>

    <p>This is the prices page</p>

    <p>{{ prices.data }}</p>

    <p>{{ pending }}</p>
    <div class="spinner-border" role="status" v-if="pending">
      <span class="visually-hidden">Loading...</span>
    </div>
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

const {data: prices} = await useFetch("http://api.dev.2dehandsbeursonline.be/api/price");

console.log(prices.value);
</script>
