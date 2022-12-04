<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title
      ><p class="placeholder-glow">
        <span v-if="listPending" class="placeholder">Lijst xxx</span>
        <span v-else>Lijst {{ list.listNumber }}</span>
      </p></template
      >
    </LayoutPageHeading>
    <div class="row">
      <div class="col-8">
        <EmployeeProductTable :products="products" @refresh="refreshProducts" :loading="productsPending"/>
      </div>
      <div class="col-4">
        <table class="table">
          <thead>
          <tr>
            <th colspan="2">Lijstinfo</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>Lijstnummer</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>{{ list.listNumber }}</span>
            </td>
          </tr>
          <tr>
            <td>Lidnummer</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>{{ emptyCheck(list.memberNumber) }}</span>
            </td>
          </tr>
          <tr>
            <td>Bevestigd</td>
            <td>
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>
                  <span v-if="list.isEmployeeValidated">
                    <i class="fa-solid fa-check"/></span
                  ><span v-else> <i class="fa-solid fa-xmark"/></span
              ></span>
            </td>
          </tr>
          <tr>
            <td>Gevalideerd</td>
            <td>
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>
                  <span v-if="list.isValidatedByEmployee">
                    <i class="fa-solid fa-check"/></span
                  ><span v-else> <i class="fa-solid fa-xmark"/></span
              ></span>
            </td>
          </tr>
          <tr>
            <td>Uitbetaald</td>
            <td>
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>TODO</span>
            </td>
          </tr>
          <tr>
            <td>Te betalen:</td>
            <td>
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>{{ toEuro(totalSold) }}</span>
            </td>
          </tr>
          </tbody>
        </table>
        <table class="table">
          <tbody>
          <tr>
            <th colspan="2">Gebruikersinfo</th>
          </tr>
          <tr>
            <td>Naam</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else>{{ user.firstname }} {{ user.lastname }}</span>
            </td>
          </tr>
          <tr>
            <td>Email</td>
            <td class="placeholder-glow w-100">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else> {{ user.email }}</span>
            </td>
          </tr>
          <tr>
            <td>Lidnummer</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else> {{ emptyCheck(user.memberNumber) }}</span>
            </td>
          </tr>
          <tr>
            <td>Telefoon</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else> {{ emptyCheck(user.phoneNumber) }}</span>
            </td>
          </tr>
          <tr>
            <td>Adres</td>
            <td class="placeholder-glow">
              <span class="w-100 placeholder" v-if="listPending"></span>
              <span v-else> {{ emptyCheck(fullAddress) }}</span>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const {pending: listPending, data, refresh} = myFetch(
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
