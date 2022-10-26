<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>{{ pageTitle }}</template>
    </LayoutPageHeading>

    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#newProductModal"
    >
      Product toevoegen
    </button>
    {{ list }}
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
  {
    initialCache: false,
    params: {
      includeProducts: true,
    },
  }
);

const list = computed(() => {
  if (!data) return null;
  if (!data.value) return null;
  return data.value.data;
});

const onProductCreated = () => {
  refresh();
};

const pageTitle = computed(() => {
  if (!list) return "Mijn lijsten";
  if (!list.value) return "Mijn lijsten";
  return `Mijn lijsten | Lijst ${list.value.listNumber}`;
});

useHead({
  title: computed(() => {
    if (!list) return "Mijn lijsten";
    if (!list.value) return "Mijn lijsten";
    return `Mijn lijsten | Lijst ${list.value.listNumber}`;
  }),
});
</script>
