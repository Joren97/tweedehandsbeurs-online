<template>
  <section class="section__list-management-detail">
    <div class="row">
      <div class="col">
        <div class="list__title placeholder-glow">
          Lijstoverzicht | <span v-if="listPending" class="placeholder">Lijst xxx</span>
          <span v-else>Lijst {{ list.listNumber }}</span>
        </div>
      </div>
      <div class="col">
        <div class="list__buttons">
          <button
            :disabled="
              listPending || (list && list.isUserConfirmed) || products.length >= 20
            "
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
            <span v-if="list && list.isUserConfirmed">Lijst is reeds bevestigd</span>
            <span v-else>Lijst bevestigen</span>
          </button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <table class="datatable">
          <thead>
            <tr>
              <th class="product__number">#</th>
              <th>Beschrijving</th>
              <th>Vraagprijs</th>
              <th>Verkoopprijs</th>
              <th>
                <span v-if="list && list.isUserConfirmed">Product is verkocht</span>
              </th>
              <th></th>
            </tr>
          </thead>
          <tbody class="placeholder-glow" v-if="listPending">
            <tr v-for="i in 5">
              <td class="placeholder-glow">
                <div class="placeholder w-100"></div>
              </td>
              <td class="placeholder-glow">
                <div class="placeholder w-100"></div>
              </td>
              <td class="placeholder-glow">
                <div class="placeholder w-100"></div>
              </td>
              <td class="placeholder-glow">
                <div class="placeholder w-100"></div>
              </td>
              <td class="placeholder-glow">
                <div class="placeholder w-100"></div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr v-for="item in products" :key="item.id">
              <td>{{ item.productNumber }}</td>
              <td>{{ item.description }}</td>
              <td>
                {{ item && item.price && toEuro(item.price.askingPrice) }}
              </td>
              <td>
                {{ item && item.price && toEuro(item.price.sellingPrice) }}
              </td>
              <td>
                <YesNoIcon :value="item.isSold" />
              </td>
              <td class="datatable__actions">
                <span class="divider"></span>
                <span class="action"><i class="fa-solid fa-pencil fa-lg"></i></span>
                <span class="action"><i class="fa-solid fa-trash fa-lg"></i></span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-4">
        <div class="list__information mb-3">
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
              <span class="item__title">Lidnummer op lijst</span>
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
              <span class="item__title">Te betalen</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ toEuro(totalSold) }}</span>
            </div>
          </div>
        </div>

        <div class="user__information">
          <div class="information__title">
            <span>Gebruikersinfo</span>
          </div>
          <div class="information__content placeholder-glow">
            <div class="information__item mb-2">
              <span class="item__title">Naam</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ user.firstname }} {{ user.lastname }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Email</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.email) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Lidnummer</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.memberNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Telefoon</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.phoneNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Adres</span>
              <span v-if="listPending" class="placeholder col-4"></span>
              <span v-else>{{ formatAdress(user) }}</span>
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

definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const deleteProductVisible = ref(false);
const editProductVisible = ref(false);
const newProductVisible = ref(false);
const confirmListVisible = ref(false);
const loading = ref(false);
const productToEdit = ref(null);
const newProductForm = ref();
const notificationStore = useNotificationStore();

clearNuxtData();

const { pending: listPending, data, refresh } = myFetch(
  `/api/productlist/${useRoute().params.id}`,
  {
    key: "list",
    params: {
      includeUser: true,
    },
  }
);

const {
  pending: productsPending,
  data: productsData,
  refresh: refreshProducts,
} = myFetch(`/api/product`, {
  key: "products",
  params: {
    perPage: 100,
    "productlistId[eq]": useRoute().params.id,
  },
});

const { data: prices, pending: pricesPending } = myLazyFetch(() => `/api/price`, {
  key: "prices",
  params: {
    perPage: 100,
  },
});

const list = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.data;
});

const user = computed(() => {
  if (!list.value) return {};
  if (!list.value.user) return {};
  return list.value.user;
});

const products = computed(() => {
  if (!productsData) return [];
  if (!productsData.value) return [];
  return productsData.value.data;
});

const totalSold = ref(0);

watch(products, () => {
  if (products.value.length > 0) {
    let total = 0.0;
    const soldProducts = products.value.filter((product) => product.isSold);
    soldProducts.forEach((product) => {
      total += product.price.askingPrice;
    });
    totalSold.value = total;
  }
});

const submitEditProduct = async () => {
  await editProductForm.value.submit();
};

const onEditProductSubmit = async (values) => {
  console.log("🚀 ~ file: [id].vue:346 ~ onEditProductSubmit ~ values:", values);
};

const onNewProductSubmit = async (values) => {
  loading.value = true;
  const body = {
    ...values,
    productlistId: useRoute().params.id,
  };

  const { data, error } = await useApi(`/api/product`, {
    method: "POST",
    body,
    initialCache: false,
  });

  if (error && error.value) {
    notificationStore.addNotification("Error", error.value.data.message);
  } else {
    notificationStore.addNotification("Success", "Product werd toegevoegd");
    refreshProducts();
  }

  newProductVisible.value = false;
  newProductForm.value.handleReset();
  loading.value = false;
};

const submitNewProduct = async () => {
  await newProductForm.value.submit();
};

const confirmList = async () => {
  loading.value = true;

  const { pending, error } = await useApi(
    `/api/productlist/confirm/${useRoute().params.id}`,
    {
      method: "PUT",
      key: "confirm",
      initialCache: false,
    }
  );

  loading.value = false;
  confirmListVisible.value = false;
  notificationStore.addNotification("Success", "De lijst werd bevestigd.");

  if (error.value != null) {
    fieldErrors.value = error.value.data.errors;
    return;
  }

  refresh();
};

const deleteProduct = () => {};

const closeNewProductModal = () => {
  newProductVisible.value = false;
  newProductForm.value.handleReset();
};
</script>
