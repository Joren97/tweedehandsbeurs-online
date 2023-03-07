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
          :text="dashboard.profit.amount"
          :percentage="dashboard.profit.previousDiffPercentage"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Lijsten"
          icon="fa-regular fa-rectangle-list"
          color="danger"
          :text="dashboard.lists.amount"
          :percentage="dashboard.lists.previousDiffPercentage"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Artikelen"
          icon="fa-solid fa-shirt"
          color="warning"
          :text="dashboard.products.amount"
          :percentage="dashboard.products.previousDiffPercentage"
        />
      </div>
      <div class="col-3">
        <StatCard
          title="Verkocht"
          text-icon="fa-solid fa-percent"
          icon="fa-solid fa-gavel"
          color="info"
          :text="dashboard.percentageSold.amount"
          :percentage="dashboard.percentageSold.previousDiffPercentage"
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

const {
  pending: dashboardPending,
  data: dashboardData,
  refresh: dashboardRefresh,
} = myLazyFetch(() => `/api/admin-dashboard`, { key: "dashboard", initialCache: false });

const dashboard = computed(() => {
  const empty = {
    profit: { amount: 0, previousDiffPercentage: 0 },
    lists: { amount: 0, previousDiffPercentage: 0 },
    products: { amount: 0, previousDiffPercentage: 0 },
    percentageSold: { amount: 0, previousDiffPercentage: 0 },
  };

  if (!dashboardData) return empty;
  if (!dashboardData.value) return empty;
  if (!dashboardData.value.data) return empty;

  return dashboardData.value.data;
});
</script>
