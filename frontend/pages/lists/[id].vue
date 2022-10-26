<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Mijn lijsten | Lijst xxx</template>
    </LayoutPageHeading>

    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#newProductModal"
    >
      Product toevoegen
    </button>
    {{ data }}
    <div
      class="modal fade"
      id="newProductModal"
      tabindex="-1"
      aria-labelledby="newProductModalLabel"
      aria-hidden="true"
    >
      <NewProductModal @product-created="onProductCreated" />
    </div>
  </div>
</template>
<script setup>
const route = useRoute();
useHead({
  title: `Mijn lijsten | Lijst ${route.params.id}`,
});
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

clearNuxtData();
const { data, pending, refresh } = myLazyFetch(
  () => `/api/productlist/me/${route.params.id}`,
  { initialCache: false }
);

const onProductCreated = () => {
  refresh();
};
</script>
