<template>
  <section class="dashboard__lists">
    <div class="lists__title">Mijn lijsten</div>

    <div class="lists__grid">
      <div class="grid__item placeholder-glow" v-if="pending" v-for="i in 4">
        <div class="item__title"><span class="placeholder col-4"></span></div>
        <div class="item__lists">
          <div class="lists__emtpy"><span class="placeholder col-12"></span></div>
        </div>
      </div>
      <div class="grid__item" v-for="item in editionsWithLists" :key="item.id" v-else>
        <div class="item__title">{{ item.year }} - {{ item.name }}</div>
        <div class="item__lists" v-if="item.lists.length > 0">
          <div
            class="lists__item"
            v-for="list in item.lists"
            :key="list.id"
            @click="openList(list.id)"
          >
            {{ list.id }} Lijst {{ list.listNumber }} ({{ list.memberNumber }})
          </div>
        </div>
        <div class="item__lists" v-else>
          <div class="lists__emtpy">Geen lijsten</div>
        </div>
      </div>
    </div>
    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#newListModal"
    >
      Nieuwe lijst toevoegen
    </button>
    <div class="modal fade d-none" id="newListModal">
      <NewListModal :active-edition="activeEdition" @list-created="onListCreated" />
    </div>
  </section>
</template>

<script setup>
useHead({
  title: "Mijn lijsten",
});
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const { pending: editionsPending, data: editionsData } = myLazyFetch(
  () => `/api/edition`,
  { key: "edition", initialCache: false }
);
const {
  pending: listsPending,
  data: listsData,
  refresh: listsRefresh,
} = myLazyFetch(() => `/api/productlist/me`, { key: "productlist", initialCache: false });

const pending = computed(() => {
  return editionsPending.value || listsPending.value;
});

const editionsWithLists = computed(() => {
  if (!editionsData) return [];
  if (!editionsData.value) return [];
  if (!listsData) return [];
  if (!listsData.value) return [];

  return editionsData.value.data.map((edition) => {
    return {
      ...edition,
      lists: listsData.value.data.filter((list) => list.editionId === edition.id),
    };
  });
});

const activeEdition = computed(() => {
  if (!editionsData) return null;
  if (!editionsData.value) return null;
  if (!editionsData.value.data) return null;

  return editionsData.value.data.find((edition) => edition.isActive);
});

const onListCreated = () => {
  listsRefresh();
};

const openList = (id) => {
  useRouter().push(`/lists/${id}`);
};
</script>
