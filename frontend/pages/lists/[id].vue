<template>
  <section class="section__lists-detail">
    <div class="row">
      <div class="col">
        <div class="lists-detail__title">{{ pageTitle }}</div>
      </div>
      <div class="col">
        <div class="lists-detail__buttons">
          <button
            :disabled="listPending || (list && list.isUserConfirmed)"
            class="btn btn-primary"
            href="#add-product-modal"
            @click="newProductVisible = true"
          >
            Product toevoegen
          </button>
          <button
            class="btn btn-primary ms-2"
            @click="confirmListVisible = true"
            :disabled="listPending || (list && list.isUserConfirmed)"
          >
            Lijst bevestigen
          </button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <div class="datatable" :class="{ 'is-loading': listPending }">
          <div class="datatable__loading">
            <div class="loading__background"></div>
            <div class="sp sp-wave"></div>
          </div>
          <table class="datatable__table">
            <thead>
              <tr>
                <th>#</th>
                <th>Beschrijving</th>
                <th>Vraagprijs</th>
                <th>Verkoopprijs</th>
                <th>
                  <span v-if="list && list.isUserConfirmed">Product is verkocht</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in products" :key="item.id">
                <td class="product__number">{{ item.productNumber }}</td>
                <td>{{ item.description }}</td>
                <td>
                  {{ item && item.price && toEuro(item.price.askingPrice) }}
                </td>
                <td>
                  {{ item && item.price && toEuro(item.price.sellingPrice) }}
                </td>
                <td v-if="list && list.isUserConfirmed">
                  <YesNoIcon :value="item.isSold" />
                </td>
                <td class="datatable__actions" v-if="list && !list.isUserConfirmed">
                  <span class="divider"></span>
                  <span class="action" @click="openEditProductModal(item.id)"
                    ><i class="fa-solid fa-pencil fa-lg"></i
                  ></span>
                  <span class="action" @click="confirmDeleteProduct(item.id)"
                    ><i class="fa-solid fa-trash fa-lg"></i
                  ></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-4">
        <div class="list__information">
          <div class="information__title">
            <span>Lijstinfo</span>
          </div>
          <div class="information__content placeholder-glow">
            <div class="information__item mb-2">
              <span class="item__title">Lijstnummer</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ list.listNumber }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Editie</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else
                >{{ list.edition && list.edition.name }}&nbsp;&dash;&nbsp;{{
                  list.edition && list.edition.year
                }}</span
              >
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Lidnummer</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(list.memberNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Bevestigd</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="list.isUserConfirmed" /></span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Gevalideerd</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="list.isEmployeeValidated" /></span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Opbrengst uitbetaald</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="list.isPaidToUser" /></span>
            </div>
            <div class="information__item">
              <span class="item__title">Jouw opbrengst</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else
                >{{ toEuro(totalSold) }}&nbsp;<span v-if="!list.memberNumber"
                  >(&nbsp;&dash;&nbsp;&euro;5 = {{ toEuro(totalSold - 5) }})</span
                ></span
              >
            </div>
          </div>
        </div>

        <TheNotification class="mt-3" />
      </div>
    </div>

    <Modal :visible="newProductVisible" @close="closeNewProductModal()">
      <template v-slot:title>Nieuw product toevoegen</template>
      <template v-slot:content>
        <ProductForm
          ref="newProductForm"
          @submit="onNewProductSubmit"
          :price-data="prices"
        />
      </template>
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary mx-2"
          @click="closeNewProductModal()"
        >
          Sluiten
        </button>
        <LoadingButton @click="submitNewProduct" :loading="loading" type="primary"
          >Product toevoegen</LoadingButton
        >
      </template>
    </Modal>
    <Modal :visible="confirmListVisible" @close="confirmListVisible = false">
      <template v-slot:title>Lijst bevestigen</template>
      <template v-slot:content
        >Ben je zeker dat je deze lijst wil bevestigen? Nadat je de lijst hebt bevestigd,
        kan je niets meer wijzigen.</template
      >
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary mx-2"
          @click="confirmListVisible = false"
        >
          Annuleren
        </button>

        <LoadingButton type="primary" @click="confirmList" :loading="loading"
          >Lijst bevestigen</LoadingButton
        >
      </template>
    </Modal>
    <Modal :visible="deleteProductVisible" @close="deleteProductVisible = false">
      <template v-slot:title>Product verwijderen</template>
      <template v-slot:content>Ben je zeker dat je dit product wil verwijderen?</template>
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary mx-2"
          @click="deleteProductVisible = false"
        >
          Annuleren
        </button>

        <LoadingButton type="primary" @click="deleteProduct" :loading="loading"
          >Product verwijderen</LoadingButton
        >
      </template>
    </Modal>
    <Modal :visible="editProductVisible" @close="closeEditProductModal()">
      <template v-slot:title>Product bewerken</template>
      <template v-slot:content>
        <ProductForm
          ref="editProductForm"
          @submit="onEditProductSubmit"
          :price-data="prices"
          :initial-data="productToEdit"
        />
      </template>
      <template v-slot:footer>
        <button
          type="button"
          class="btn btn-secondary mx-2"
          @click="closeEditProductModal()"
        >
          Sluiten
        </button>
        <LoadingButton @click="submitEditProduct" :loading="loading" type="primary"
          >Wijzigingen opslaan</LoadingButton
        >
      </template>
    </Modal>
  </section>
</template>
<script setup>
import { useNotificationStore } from "~~/store/notification";
const notificationStore = useNotificationStore();
const loading = ref(false);

/* Initial data */
clearNuxtData();

const { data: prices, pending: pricesPending } = myLazyFetch(() => `/api/price`, {
  key: "prices",
  params: {
    perPage: 100,
  },
});

const { data: listData, pending: listPending, refresh } = myLazyFetch(
  () => `/api/productlist/me/${useRoute().params.id}`,
  {
    key: "productlist",
    initialCache: false,
    params: {
      includeProducts: true,
      includeEdition: true,
    },
  }
);

const list = computed(() => {
  if (!listData) return null;
  if (!listData.value) return null;
  return listData.value.data;
});

const products = computed(() => {
  if (!list.value) return [];
  return list.value.products;
});

const totalSold = computed(() => {
  if (!products.value) return 0;
  if (products.value.length < 1) return 0;
  // Return the total price of all products sold
  let sum = 0;
  products.value.forEach((product) => {
    if (product.isSold) sum += product.price.askingPrice;
  });
  return sum;
});
/* End initial data */

/* Page info */
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const pageTitle = computed(() => {
  if (!list || !list.value) return "Mijn lijsten";
  return `Mijn lijsten | Lijst ${list.value.listNumber}`;
});

useHead({
  title: pageTitle,
});
/* End page info */

/* Add new product */
const newProductForm = ref();
const newProductVisible = ref(false);

const submitNewProduct = async () => {
  await newProductForm.value.submit();
};

const onNewProductSubmit = async (values) => {
  loading.value = true;
  const body = {
    ...values,
    productlistId: useRoute().params.id,
  };

  const { data, error } = await useApi(`/api/product/me`, {
    method: "POST",
    body,
    initialCache: false,
  });

  if (error && error.value) {
    notificationStore.addNotification("Error", error.value.data.message);
  } else {
    notificationStore.addNotification("Success", "Product werd toegevoegd");
    refresh();
  }

  newProductVisible.value = false;
  newProductForm.value.handleReset();
  loading.value = false;
};

const closeNewProductModal = () => {
  newProductVisible.value = false;
  newProductForm.value.handleReset();
};
/* End add new product */

/* Edit product */
const productToEdit = ref(null);
const editProductForm = ref();
const editProductVisible = ref(false);

const openEditProductModal = (productId) => {
  productToEdit.value = products.value.find((p) => p.id === productId);
  editProductVisible.value = true;
};

const submitEditProduct = async () => {
  await editProductForm.value.submit();
};

const onEditProductSubmit = async (values) => {
  loading.value = true;
  const body = {
    ...values,
    productlistId: useRoute().params.id,
  };

  const { data, error } = await useApi(`/api/product/me/${productToEdit.value.id}`, {
    method: "PUT",
    body,
    initialCache: false,
  });

  editProductVisible.value = false;
  editProductForm.value.handleReset();
  loading.value = false;

  await nextTick();

  if (error && error.value) {
    notificationStore.addNotification("Error", error.value.data.message);
  } else {
    notificationStore.addNotification("Success", "Product werd bewerkt");
    refresh();
  }
};

const closeEditProductModal = () => {
  editProductVisible.value = false;
  productToEdit.value = null;
};
/* End edit product */

/* Delete product */
const deleteProductVisible = ref(false);
const selectedProductId = ref(null);

const confirmDeleteProduct = (productId) => {
  selectedProductId.value = productId;
  deleteProductVisible.value = true;
};

const deleteProduct = async () => {
  loading.value = true;
  const { pending, error } = await useApi(`/api/product/me/${selectedProductId.value}`, {
    method: "DELETE",
    key: "delete",
    initialCache: false,
  });

  if (error.value != null) {
    notificationStore.addNotification("Error", error.value.data.message);
  } else {
    notificationStore.addNotification("Success", "Product werd verwijderd");
  }

  deleteProductVisible.value = false;
  loading.value = false;
  selectedProductId.value = null;
  refresh();
};
/* End delete product */

/* Confirm list */
const confirmListVisible = ref(false);

const confirmList = async () => {
  loading.value = true;

  const { pending, error } = await useApi(
    `/api/productlist/me/confirm/${useRoute().params.id}`,
    {
      method: "PUT",
      key: "confirm",
      initialCache: false,
    }
  );

  loading.value = false;
  confirmListVisible.value = false;
  notificationStore.addNotification(
    "Success",
    "De lijst werd bevestigd. Je ontvangt zometeen een emailbevestiging. Je kan nu je etikken voor deze lijst invullen."
  );

  if (error.value != null) {
    fieldErrors.value = error.value.data.errors;
    return;
  }

  refresh();
};
/* End confirm list */
</script>
