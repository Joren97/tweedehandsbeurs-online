<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Mijn lijsten | Lijst xxx</template>
    </LayoutPageHeading>

    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#newListModal"
    >
      Nieuwe lijst toevoegen
    </button>
    {{ data }}
    <div
      class="modal fade"
      id="newListModal"
      tabindex="-1"
      aria-labelledby="newListModalLabel"
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

const {
  data,
  pending,
  refresh,
} = useCustomLazyFetch(`/api/productlist/me/${route.params.id}`, { key: "edition" });

const onProductCreated = () => {
  refresh();
};
</script>
