<template>
  <div>
    <section class="section__list-management">
      <div class="row">
        <div class="col">
          <div class="list-management__title">Lijstoverzicht</div>
        </div>
      </div>

      <div class="list-management__filters">
        <div class="row align-items-center mb-3">
          <div class="col">
            <div class="filters__search">
              <input
                class="form-control"
                name="search"
                id="search"
                @input="keywordChange"
                placeholder="Zoeken"
                :disabled="listsPending || editionsPending"
              />
            </div>
          </div>

          <div class="col">
            <div class="filters filters__confirmed">
              <p>Bevestigd</p>
              <select
                class="form-select"
                v-model="isUserConfirmed"
                :disabled="listsPending || editionsPending"
              >
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="filters filters__validated">
              <p>Gevalideerd</p>

              <select
                class="form-select"
                v-model="isEmployeeValidated"
                :disabled="listsPending || editionsPending"
              >
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="filters filters__paid">
              <p>Uitbetaald</p>
              <select
                class="form-select"
                v-model="isPaidToUser"
                :disabled="listsPending || editionsPending"
              >
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
          <div class="col">
            <div class="filters filters__paid">
              <p>Editie</p>
              <select
                class="form-select"
                v-model="selectedEdition"
                :disabled="listsPending || editionsPending"
              >
                <option :value="-1">Huidige editie</option>
                <option :value="0">Alle edities</option>
                <option v-for="edition in editions" :key="edition.id" :value="edition.id">
                  {{ edition.name }} - {{ edition.year }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col">
          <div class="datatable" :class="{ 'is-loading': listsPending }">
            <div class="datatable__loading">
              <div class="loading__background"></div>
              <div class="sp sp-wave"></div>
            </div>
            <table class="datatable__table">
              <thead>
                <tr>
                  <th>Lijstnummer</th>
                  <th>Gebruiker</th>
                  <th>Lidnummer</th>
                  <th>Bevestigd</th>
                  <th>Gevalideerd</th>
                  <th>Uitbetaald</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in lists">
                  <td>{{ item.listNumber }}</td>
                  <td>
                    <NuxtLink :to="`/user-management/${item.user.id}`"
                      >{{ item.user && item.user.firstname }}
                      {{ item.user && item.user.lastname }}</NuxtLink
                    >
                  </td>
                  <td>{{ emptyCheck(item.memberNumber) }}</td>
                  <td>
                    <YesNoIcon :value="item.isUserConfirmed" />
                  </td>
                  <td>
                    <YesNoIcon :value="item.isEmployeeValidated" />
                  </td>
                  <td>
                    <YesNoIcon :value="item.isPaidToUser" />
                  </td>
                  <td class="datatable__actions">
                    <span class="divider"></span>
                    <span class="action">
                      <i class="fa-solid fa-eye"></i>
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <Pagination :pagination="pagination" />
    </section>
  </div>
</template>
<script setup>
useHead({
  title: "Lijstenbeheer",
});
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const search = ref("");
const isUserConfirmed = ref("any");
const isEmployeeValidated = ref("any");
const isPaidToUser = ref("any");
const selectedEdition = ref(-1);
const query = computed(() => {
  let query = "&isUserConfirmed[]=0&isUserConfirmed[]=1";
  query += "&isEmployeeValidated[]=0&isEmployeeValidated[]=1";
  query += "&isPaidToUser[]=0&isPaidToUser[]=1";
  query += "&editionId=" + selectedEdition.value;
  if (isUserConfirmed.value == "yes") query = query.replace("isUserConfirmed[]=0", "");
  if (isUserConfirmed.value == "no") query = query.replace("isUserConfirmed[]=1", "");
  if (isEmployeeValidated.value == "yes")
    query = query.replace("isEmployeeValidated[]=0", "");
  if (isEmployeeValidated.value == "no")
    query = query.replace("isEmployeeValidated[]=1", "");
  if (isPaidToUser.value == "yes") query = query.replace("isPaidToUser[]=0", "");
  if (isPaidToUser.value == "no") query = query.replace("isPaidToUser[]=1", "");

  return query;
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending: listsPending, data: listsData } = myAsyncData(
  () =>
    `/api/productlist?page=${page.value}&includeUser=true&search=${search.value}${query.value}`,
  {},
  {
    watch: [page, search, query],
    initialCache: false,
    key: "productlists",
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

const pagination = computed(() => {
  if (!listsData) return {};
  if (!listsData.value) return {};
  return listsData.value.meta;
});

const lists = computed(() => {
  if (!listsData) return [];
  if (!listsData.value) return [];
  return listsData.value.data;
});

const debounce = ref(null);

const keywordChange = (e) => {
  clearTimeout(debounce.value);
  debounce.value = setTimeout(() => {
    search.value = e.target.value;
  }, 500);
};
</script>
