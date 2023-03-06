<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Beursoverzicht</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col-3">
        <StatCard
          color="success"
          title="Winst"
          icon="fa-solid fa-sack-dollar"
          text-icon="fa-solid fa-euro-sign"
          text="1.254,00"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Lijsten"
          text="12"
          icon="fa-regular fa-rectangle-list"
          color="danger"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Artikelen"
          text="12000"
          icon="fa-solid fa-shirt"
          color="warning"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Verkocht"
          text="30"
          text-icon="fa-solid fa-percent"
          icon="fa-solid fa-gavel"
          color="info"
        />
      </div>
    </div>
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
useHead({
  title: "Beursoverzicht",
});
</script>
