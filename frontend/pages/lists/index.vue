<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>List</template>
    </LayoutPageHeading>
    <p>This is the list page</p>
    <div class="row">
      <div class="col-3" v-for="item in editionWithLists">
        <div class="card">
          <div class="card-header">
            {{ item.id }} &dash;{{ item.year }} &dash; {{ item.name }}
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item" v-for="item in item.lists">
              {{ item.listNumber }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <p>{{ editionWithLists }}</p>
  <br />
  <p>{{ lists }}</p>
</template>
<script setup>
definePageMeta({
  layout: "authorized",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const [
  { pending: editionsPending, data: editionsData },
  { pending: listsPending, data: listsData },
] = await Promise.all([
  useCustomLazyFetch(`/api/edition`),
  useCustomLazyFetch(`/api/productlist/me`),
]);
</script>
