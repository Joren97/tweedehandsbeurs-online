<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Lijst 101</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col-8">
        {{ list.products }}
      </div>
      <div class="col-4">
        {{ list.user }}
      </div>
    </div>
    {{ pending }}
    {{ list }}
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

const { pending, data } = useLazyAsyncData("list", () =>
  $fetch(
    `${useRuntimeConfig().public.API_BASE_URL}/api/productlist/${
      useRoute().params.id
    }?includeUser=true&includeProducts=true`
  )
);

const list = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.data;
});
</script>
