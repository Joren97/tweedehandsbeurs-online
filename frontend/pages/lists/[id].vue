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
    <button
      class="btn btn-primary"
      @click="confirmList"
      :disabled="pending || (list && list.data.isUserConfirmed)"
    >
      Lijst bevestigen
    </button>
    <p>{{ list }}</p>
    <p>{{ prices }}</p>
    <div
      class="modal fade"
      id="newProductModal"
      tabindex="-1"
      aria-labelledby="newProductModalLabel"
      aria-hidden="true"
    >
      <NewProductModal @product-created="onProductCreated" :price-data="prices" />
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

const { data: list, pending: listPending, refresh } = myLazyFetch(
  () => `/api/productlist/me/${route.params.id}`,
  {
    key: "productlist",
    initialCache: false,
    params: {
      includeProducts: true,
    },
  }
);

const { data: prices, pending: pricesPending } = myLazyFetch(() => `/api/price`, {
  key: "prices",
  params: {
    perPage: 100,
  },
});

const confirmList = async () => {
  const body = {
    isUserConfirmed: true,
  };

  const { pending, error } = await myFetch(
    () => `/api/productlist/me/${route.params.id}`,
    {
      method: "PUT",
      body,
      key: "confirm",
      initialCache: false,
    }
  );

  if (error.value != null) {
    console.log(error.value);
    fieldErrors.value = error.value.data.errors;
    return;
  }

  refresh();
};

const onProductCreated = () => {
  refresh();
};

const pageTitle = computed(() => {
  if (!list) return "Mijn lijsten";
  if (!list.value) return "Mijn lijsten";
  const data = list.value.data;
  return `Mijn lijsten | Lijst ${data.listNumber}`;
});

useHead({
  title: pageTitle,
});
</script>
