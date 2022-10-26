<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Lijstenbeheer</template>
    </LayoutPageHeading>
    {{ pending }}
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
            <NuxtLink class="btn btn-primary btn-sm" :to="`/list-management/${item.id}`">
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
    <p>This is the list management page</p>
  </div>
</template>
<script setup>
useHead({
  title: "Lijstenbeheer",
});
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data } = myAsyncData(
  () => `/api/productlist?page=${page.value}&includeUser=true`,
  {},
  {
    watch: [page],
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
</script>
