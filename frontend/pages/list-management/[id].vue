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
        <!-- <EmployeeProductTable
          :prices="prices"
          :products="products"
          @refresh="refreshProducts"
          :loading="productsPending || pricesPending"
        /> -->
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
      </div>
    </div>
  </section>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

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

const { pending: pricesPending, data: pricesData, refresh: refreshPrices } = myFetch(
  `/api/price`,
  {
    key: "prices",
    params: {
      perPage: 100,
    },
  }
);

const prices = computed(() => {
  if (!pricesData) return [];
  if (!pricesData.value) return [];
  return pricesData.value.data;
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

const fullAddress = computed(() => {
  if (!user.value) return "";
  if (user.value.address && user.value.postalCode && user.value.city) {
    return `${user.value.address}, ${user.value.postalCode} ${user.value.city}`;
  }
  return "";
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
</script>
