<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Prijslijst</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col-12">
        <p>Hier vind je een overzicht van alle prijzen met bijhorende verkoopprijs.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-3" v-for="array in pricesAsThreeArrays">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Vraagprijs</th>
              <th>Verkoopprijs</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in array" :key="item.id">
              <td>{{ toEuro(item.askingPrice) }}</td>
              <td>{{ toEuro(item.sellingPrice) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const { data, pending } = myFetch(() => `/api/price`, {
  params: {
    perPage: 100,
  },
});

const prices = computed(() => {
  return data && data.value && data.value.data;
});

const pricesAsThreeArrays = computed(() => {
  const x = [];
  const chunkSize = 25;
  for (let i = 0; i < prices.value.length; i += chunkSize) {
    const chunk = prices.value.slice(i, i + chunkSize);
    // do whatever
    x.push(chunk);
  }
  return x;
});
</script>
