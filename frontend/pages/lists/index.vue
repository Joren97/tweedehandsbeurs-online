<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Mijn lijsten</template>
    </LayoutPageHeading>
    <div class="row row-cols-4">
      <div class="col mb-3" v-for="item in editionsWithLists">
        <div class="card">
          <div class="card-header">{{ item.year }} &dash; {{ item.name }}</div>
          <ul class="list-group list-group-flush">
            <template v-if="item.lists.length > 0">
              <li class="list-group-item pe-clickable" v-for="item in item.lists">
                Lijst {{ item.listNumber }}
                <span v-if="item.memberNumber" class="text-muted small"
                  >({{ item.memberNumber }})</span
                >
              </li>
            </template>
            <template v-else><li class="list-group-item">Geen lijsten</li> </template>
          </ul>
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
    <div
      class="modal fade"
      id="newListModal"
      tabindex="-1"
      aria-labelledby="newListModalLabel"
      aria-hidden="true"
    >
      <NewListModal />
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const {
  pending: editionsPending,
  data: editionsData,
} = useCustomLazyFetch(`/api/edition`, { key: "edition" });
const {
  pending: listsPending,
  data: listsData,
} = useCustomLazyFetch(`/api/productlist/me`, { key: "productlist" });

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
</script>
