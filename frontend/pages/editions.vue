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
            <button class="btn btn-primary btn-sm" @click="activate(item, true)">
              Activate
            </button>
            <button class="btn btn-primary btn-sm" @click="activate(item, false)">
              Deactivate
            </button>
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

const { pending, data, refresh: refreshEditions } = myAsyncData(
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
const activate = async (edition, isActive) => {
  const res = await useAPI(`/api/edition/${edition.id}`, {
    method: "PUT",
    body: {
      ...edition,
      isActive,
    },
  });
  if (res.status === "Success") {
    refreshEditions();
  }
};
</script>
