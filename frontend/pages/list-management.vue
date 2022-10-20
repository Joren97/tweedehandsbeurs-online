<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Lijstenbeheer</template>
    </LayoutPageHeading>
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
    <!-- <ThePagination test="hello" /> -->
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

const { pending, data } = useCustomLazyFetch("/api/productlist?includeUser=true");
const lists = computed(() => {
  if (!data.value) return [];
  return data.value.data;
});
</script>
