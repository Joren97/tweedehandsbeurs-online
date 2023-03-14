<template>
  <section class="section__user-management">
    <div class="row">
      <div class="col-9">
        <div class="user-management__title">Gebruikers</div>
      </div>
      <div class="col">
        <div class="user-management__search">
          <SearchInput />
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col">
        <div class="datatable" :class="{ 'is-loading': pending }">
          <div class="datatable__loading">
            <div class="loading__background"></div>
            <div class="sp sp-wave"></div>
          </div>
          <table class="datatable__table">
            <thead>
              <tr>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Email</th>
                <th>Lidnummer</th>
                <th>Telefoon</th>
                <th>Adres</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>{{ emptyCheck(user.firstname) }}</td>
                <td>{{ emptyCheck(user.lastname) }}</td>
                <td>{{ emptyCheck(user.email) }}</td>
                <td>{{ emptyCheck(user.memberNumber) }}</td>
                <td>{{ user.phoneNumber }}</td>
                <td>{{ formatAdress(user) }}</td>
                <td class="datatable__actions">
                  <span class="divider"></span>
                  <span class="action"
                    ><NuxtLink :to="`/user-management/${user.id}`">
                      <i class="fa-regular fa-eye fa-lg"></i> </NuxtLink
                  ></span>
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

const query = computed(() => {
  return "";
});

const search = computed(() => {
  return useRoute().query.search || "";
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data } = myAsyncData(
  () => `/api/user?page=${page.value}&search=${search.value}`,
  {},
  {
    watch: [page, query, search],
    initialCache: false,
    key: "user-management",
  }
);

const users = computed(() => {
  if (!data) return [];
  if (!data.value) return [];
  return data.value.data;
});

const formatAdress = (user) => {
  const address = user.address;
  const postalCode = user.postalCode;
  const city = user.city;

  // If all three are empty, return a dash
  if (!address && !postalCode && !city) return "-";
  // If postalCode and city is empty, return address
  if (!postalCode && !city) return address;
  // If address is empty, return postalCode and city
  if (!address) return `${postalCode} ${city}`;
  // Return address, postalCode and city
  return `${user.address}, ${user.postalCode} ${user.city}`;
};

const pagination = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.meta;
});

useHead({
  title: "Gebruikers",
});
</script>
