<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Prijslijst</template>
    </LayoutPageHeading>
    <p>Hier vind je een overzicht van alle prijzen met bijhorende verkoopprijs.</p>
    <table class="table">
      <thead>
      <tr>
        <th>Vraagprijs</th>
        <th>Verkoopprijs</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in prices" :key="item.id">
        <td>{{ toEuro(item.askingPrice) }}</td>
        <td>{{ toEuro(item.sellingPrice) }}</td>
      </tr>
      </tbody>
    </table>

    <!--    <p>{{ prices.data }}</p>-->

    <!--    <p>{{ pending }}</p>-->
    <!--    <div class="spinner-border" role="status" v-if="pending">-->
    <!--      <span class="visually-hidden">Loading...</span>-->
    <!--    </div>-->

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

const {data, pending} = myFetch(() => `/api/price`, {
  params: {
    perPage: 100,
  },
});


const prices = computed(() => {
          return data && data.value && data.value.data;
        }
    )
;

console.log(prices);

</script>
