<template>
  <section class="section__product-management">
    <div class="row">
      <div class="col-6">
        <div class="product-management__title">Productoverzicht</div>
      </div>
      <div class="col">
        <SearchInput />
      </div>
      <div class="col">
        <select
          class="form-select"
          v-model="selectedEdition"
          :disabled="productsPending || editionsPending"
        >
          <option :value="-1">Huidige editie</option>
          <option :value="0">Alle edities</option>
          <option v-for="edition in editions" :key="edition.id" :value="edition.id">
            {{ edition.name }} - {{ edition.year }}
          </option>
        </select>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col">
        <div class="datatable" :class="{ 'is-loading': productsPending }">
          <div class="datatable__loading">
            <div class="loading__background"></div>
            <div class="sp sp-wave"></div>
          </div>
          <table class="datatable__table">
            <thead>
              <tr>
                <th>Productnummer</th>
                <th>Lijstnummer</th>
                <th>Beschrijving</th>
                <th>Vraagprijs</th>
                <th>Verkoopprijs</th>
                <th>Verkocht</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in products" :key="item.id">
                <td>{{ item.productNumber }}</td>
                <td>{{ item.productlist && item.productlist.listNumber }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price && toEuro(item.price.askingPrice) }}</td>
                <td>{{ item.price && toEuro(item.price.sellingPrice) }}</td>
                <td><YesNoIcon :value="item.isSold" /></td>
                <td class="datatable__actions">
                  <span class="divider"></span>
                  <span class="action" @click="openList(item)">
                    <NuxtLink :to="`/list-management/${item.productlist.id}`">
                      <i class="fa-regular fa-eye fa-lg"></i>
                    </NuxtLink>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <Pagination :pagination="pagination" />
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

const selectedEdition = ref(-1);

const query = computed(() => {
  let query = "&editionId=" + selectedEdition.value;
  return query;
});

const search = computed(() => {
  return useRoute().query.search || "";
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending: productsPending, data } = myAsyncData(
  () => `/api/product?page=${page.value}&description[like]=${search.value}${query.value}`,
  {},
  {
    watch: [page, query, search],
    initialCache: false,
    key: "product-management",
  }
);

const {
  pending: editionsPending,
  data: editionData,
  refresh: refreshEditions,
} = myLazyFetch(() => `/api/edition`, {
  key: "editions",
  initialCache: false,
});

const editions = computed(() => {
  if (!editionData) return [];
  if (!editionData.value) return [];
  return editionData.value.data;
});

const products = computed(() => {
  if (!data) return [];
  if (!data.value) return [];
  return data.value.data;
});

const pagination = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.meta;
});
</script>
