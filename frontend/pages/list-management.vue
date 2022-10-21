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
        </tr>
      </tbody>
    </table>
    <Pagination
      :page="pagination.current_page"
      :from="pagination.from"
      :to="pagination.to"
      :total="pagination.total"
      :last-page="pagination.last_page"
      @previous-page="previousPage"
      @next-page="nextPage"
    />
    <p>This is the list management page</p>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const page = ref(1);

const route = useRoute();
const config = useRuntimeConfig();

const { pending, data, refresh } = useLazyAsyncData(
  `productlists`,
  () =>
    $fetch(
      `${config.public.API_BASE_URL}/api/productlist?page=${page.value}&includeUser=true`
    ),
  { watch: [page] }
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

const previousPage = () => {
  page.value = page.value - 1;
};

const nextPage = () => {
  page.value = page.value + 1;
};
</script>
