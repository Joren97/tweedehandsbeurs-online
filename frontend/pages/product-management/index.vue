<template>
  <section class="section__product-management">
    <div class="row">
      <div class="col-9">
        <div class="product-management__title">Productoverzicht</div>
      </div>
      <div class="col">
        <div class="product-management__search">
          <input
            class="form-control"
            name="search"
            id="search"
            @input="keywordChange"
            placeholder="Zoeken"
          />
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col">
        <table class="products__table datatable">
          <thead>
            <tr>
              <th>Productnummer</th>
              <th>Lijstnummer</th>
              <th>Beschrijving</th>
              <th>Vraagprijs</th>
              <th>Verkoopprijs</th>
              <th></th>
            </tr>
          </thead>
          <tbody v-if="pending" class="placeholder-glow">
            <tr v-for="i in 15">
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td></td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr v-for="item in products" :key="item.id">
              <td>{{ item.productNumber }}</td>
              <td>{{ item.productlist && item.productlist.listNumber }}</td>
              <td>{{ item.description }}</td>
              <td>{{ item.price && toEuro(item.price.askingPrice) }}</td>
              <td>{{ item.price && toEuro(item.price.sellingPrice) }}</td>
              <td class="datatable__actions">
                <span class="divider"></span>
                <span class="action"><i class="fa-regular fa-eye fa-lg"></i></span>
              </td>
            </tr>
          </tbody>
        </table>
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

const query = computed(() => {
  return "";
});

const search = ref("");

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data } = myAsyncData(
  () => `/api/product?page=${page.value}&description[like]=${search.value}`,
  {},
  {
    watch: [page, query, search],
    initialCache: false,
    key: "product-management",
  }
);

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

const debounce = ref(null);

const keywordChange = (e) => {
  clearTimeout(debounce.value);
  debounce.value = setTimeout(() => {
    search.value = e.target.value;
  }, 500);
};
</script>
