<template>
  <div>
    <LayoutPageHeading> <template v-slot:title>Edities</template> </LayoutPageHeading
    >{{ pending }}
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Voornaam</th>
          <th scope="col">Naam</th>
          <th scope="col">Lidnummer</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in editions">
          <td>{{ item.name }}</td>
          <td>{{ item.year }}</td>
          <td>
            <span v-if="item.isActive"> <i class="fa-solid fa-check" /></span
            ><span v-else> <i class="fa-solid fa-xmark" /></span>
          </td>
          <td>
            <NuxtLink class="btn btn-primary btn-sm" :to="`/list-management/${item.id}`">
              Activate
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
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "admin",
  },
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data } = myAsyncData(
  () => `/api/edition?page=${page.value}`,
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

const editions = computed(() => {
  if (!data) return [];
  if (!data.value) return [];
  return data.value.data;
});
</script>
