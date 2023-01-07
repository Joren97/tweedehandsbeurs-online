<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>{{ pageTitle }}</template>
    </LayoutPageHeading>

    <div class="row">
      <div class="col-8">
        <table class="">
          <thead>
            <tr>
              <th>#</th>
              <th>Beschrijving</th>
              <th>Vraagprijs</th>
              <th>Verkoopprijs</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in products" :key="item.id">
              <td>{{ item.productNumber }}</td>
              <td>{{ item.description }}</td>
              <td>{{ toEuro(item.price.askingPrice) }}</td>
              <td>{{ toEuro(item.price.sellingPrice) }}</td>
              <td>
                <button class="btn btn-primary" @click="deleteProduct(item.id)">
                  <!-- Delete icon -->
                  <i class="fa-regular fa-trash-can"></i>
                </button>
                <button
                  class="btn btn-secondary"
                  @click="productToEditId = item.id"
                  data-bs-toggle="modal"
                  data-bs-target="#editProductModal"
                >
                  <i class="fa-solid fa-pencil"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-4">
        <table class="table">
          <tbody v-if="list">
            <tr>
              <th>Lijstnummer</th>
              <td>{{ list.listNumber }}</td>
            </tr>
            <tr>
              <th>Lidnummer</th>
              <td>{{ emptyCheck(list.memberNumber) }}</td>
            </tr>
            <tr>
              <th>Bevestigd</th>
              <td>{{ list.isUserConfirmed }}</td>
            </tr>
            <tr>
              <th>Gevalideerd</th>
              <td>{{ list.isEmployeeValidated }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <button
      class="btn btn-primary"
      href="#add-product-modal"
      @click="newProductVisible = true"
    >
      Product toevoegen
    </button>
    <button
      class="btn btn-primary"
      @click="confirmList"
      :disabled="listPending || (list && list.isUserConfirmed)"
    >
      Lijst bevestigen
    </button>
    <Modal :visible="newProductVisible">
      <template v-slot:title>Nieuw product toevoegen</template>
      <template v-slot:content
        ><NewProductForm
          ref="newProductForm"
          @product-created="onProductCreated"
          :price-data="prices"
      /></template>
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary"
          @click="newProductVisible = false"
        >
          Sluiten
        </button>
        <button type="button" class="btn btn-primary" @click="submitProduct">
          Product toevoegen
        </button>
      </template>
    </Modal>
    <div
      class="modal fade"
      id="editProductModal"
      tabindex="-1"
      aria-labelledby="editProductModal"
      aria-hidden="true"
    >
      <EditProductModal
        @product-updated="onProductUpdated"
        :price-data="prices"
        :product-to-edit="productToEdit"
      />
    </div>
  </div>
</template>
<script setup>
const route = useRoute();
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const newProductForm = ref();
const newProductVisible = ref(false);

clearNuxtData();

const submitProduct = () => {
  newProductForm.value.submit();
};

const productToEditId = ref(0);
const productToEdit = computed(() => {
  if (productToEditId.value === 0) {
    return null;
  }
  return products.value.find((p) => p.id === productToEditId.value);
});

const { data: listData, pending: listPending, refresh } = myLazyFetch(
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
  const { pending, error } = await myFetch(
    () => `/api/productlist/me/confirm/${route.params.id}`,
    {
      method: "PUT",
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

const deleteProduct = async (productId) => {
  const { pending, error } = await myFetch(() => `/api/product/me/${productId}`, {
    method: "DELETE",
    key: "delete",
    initialCache: false,
  });

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

const onProductUpdated = () => {
  refresh();
};

const pageTitle = computed(() => {
  if (!list || !list.value) return "Mijn lijsten";
  return `Mijn lijsten | Lijst ${list.value.listNumber}`;
});

const list = computed(() => {
  if (!listData) return null;
  if (!listData.value) return null;
  return listData.value.data;
});

const products = computed(() => {
  if (!list.value) return [];
  return list.value.products;
});

useHead({
  title: pageTitle,
});
</script>
