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
            <tr v-for="i in 15" class="placeholder-glow" v-if="pending">
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
              <td><span class="placeholder w-100"></span></td>
            </tr>
            <tr v-for="item in lists" v-else>
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
            v-model="isUserConfirmed"
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
            v-model="isUserConfirmed"
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
            v-model="isUserConfirmed"
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
            v-model="isEmployeeValidated"
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
            v-model="isEmployeeValidated"
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
            v-model="isEmployeeValidated"
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
            v-model="isPaidToUser"
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
            v-model="isPaidToUser"
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
            v-model="isPaidToUser"
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
const isUserConfirmed = ref("any");
const isEmployeeValidated = ref("any");
const isPaidToUser = ref("any");
const query = computed(() => {
  let query = "&isUserConfirmed[]=0&isUserConfirmed[]=1";
  query += "&isEmployeeValidated[]=0&isEmployeeValidated[]=1";
  query += "&isPaidToUser[]=0&isPaidToUser[]=1";
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

const { pending, data } = myAsyncData(
  () =>
    `/api/productlist?page=${page.value}&includeUser=true&search=${search.value}${query.value}`,
  {},
  {
    watch: [page, search, query],
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
