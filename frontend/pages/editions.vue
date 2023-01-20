<template>
  <section>
    <LayoutPageHeading> <template v-slot:title>Edities</template> </LayoutPageHeading>

    <div class="row mb-3">
      <div class="col">
        <div class="datatable" :class="{ 'is-loading': loading || pending }">
          <div class="datatable__loading">
            <div class="loading__background"></div>
            <div class="sp sp-wave"></div>
          </div>
          <table class="datatable__table">
            <thead>
              <tr>
                <th>Naam</th>
                <th>Jaar</th>
                <th>Actief</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in editions">
                <td>{{ item.name }}</td>
                <td>{{ item.year }}</td>
                <td>
                  <YesNoIcon :value="item.isActive" />
                </td>
                <td class="datatable__actions">
                  <span class="divider"></span>
                  <span
                    class="action"
                    :class="{ disabled: item.isActive }"
                    @click="activate(item, true)"
                    ><i class="fa-solid fa-turn-up"></i
                  ></span>
                  <span
                    class="action"
                    :class="{ disabled: !item.isActive }"
                    @click="activate(item, false)"
                  >
                    <i class="fa-solid fa-arrow-turn-down"></i
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
    authLevel: "admin",
  },
});

useHead({
  title: "Edities",
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const { pending, data, refresh: refreshEditions } = myAsyncData(
  () => `/api/edition?page=${page.value}`,
  {},
  {
    watch: [page],
    key: "editions",
    initialCache: false,
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

const loading = ref(false);

const activate = async (edition, isActive) => {
  loading.value = true;
  const { data: resData } = await useApi(`/api/edition/${edition.id}`, {
    method: "PUT",
    body: {
      ...edition,
      isActive,
    },
  });
  const { data, message, status } = resData.value;

  if (status === "Success") {
    refreshEditions();
  }
  loading.value = false;
};
</script>
