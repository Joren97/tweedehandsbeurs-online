<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Lijstenbeheer</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Voornaam</th>
              <th scope="col">Naam</th>
              <th scope="col">Lidnummer</th>
              <th scope="col">Bevestigd</th>
              <th scope="col">Gevalideerd</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in lists">
              <th scope="row">{{ item.listNumber }}</th>
              <td>{{ item.user.firstname }}</td>
              <td>{{ item.user.lastname }}</td>
              <td>{{ emptyCheck(item.memberNumber) }}</td>
              <td>{{ item.isUserConfirmed }}</td>
              <td>{{ item.isEmployeeValidated }}</td>
              <td>
                <NuxtLink
                  class="btn btn-primary btn-sm"
                  :to="`/list-management/${item.id}`"
                >
                  <i class="fas fa-eye"></i>
                </NuxtLink>
              </td>
            </tr>
          </tbody>
        </table>
        <Pagination
          :page="pagination.current_page"
          :from="pagination.from"
          :to="pagination.to"
          :total="pagination.total"
          :last-page="pagination.last_page"
        />
      </div>
      <div class="col-3">
        <div class="form-floating mb-3">
          <input class="form-control" id="search" @input="keywordChange" />
          <label for="search">Zoekterm</label>
        </div>
        <p>Bevestigd</p>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="confirmed"
            id="confirmed-yes"
            value="yes"
          />
          <label class="form-check-label" for="confirmed-yes">Ja</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="confirmed"
            id="confirmed-no"
            value="no"
          />
          <label class="form-check-label" for="confirmed-no">Nee</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="confirmed"
            id="confirmed-any"
            value="any"
          />
          <label class="form-check-label" for="confirmed-any">Alles</label>
        </div>
        <p>Gevalideerd</p>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="validated"
            id="validated-yes"
            value="yes"
          />
          <label class="form-check-label" for="validated-yes">Ja</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="validated"
            id="validated-no"
            value="no"
          />
          <label class="form-check-label" for="validated-no">Nee</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="validated"
            id="validated-any"
            value="any"
          />
          <label class="form-check-label" for="validated-any">Alles</label>
        </div>
        <p>Uitbetaald</p>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paid"
            id="paid-yes"
            value="yes"
          />
          <label class="form-check-label" for="paid-yes">Ja</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paid"
            id="paid-no"
            value="no"
          />
          <label class="form-check-label" for="paid-no">Nee</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input"
            type="radio"
            name="paid"
            id="paid-any"
            value="any"
          />
          <label class="form-check-label" for="paid-any">Alles</label>
        </div>
      </div>
    </div>
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

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data } = myAsyncData(
  () => `/api/productlist?page=${page.value}&includeUser=true&search=${search.value}`,
  {},
  {
    watch: [page, search],
  }
);

const pagination = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.meta;
});

const lists = computed(() => {
  if (!data) return [];
  if (!data.value) return [];
  return data.value.data;
});

const debounce = ref(null);

const keywordChange = (e) => {
  clearTimeout(debounce.value);
  debounce.value = setTimeout(() => {
    search.value = e.target.value;
  }, 500);
};
</script>
