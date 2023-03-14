<template>
  <div class="datatable__pagination">
    <div class="previous action" @click="previousPage()">
      <span class="me-3"><i class="fa-solid fa-chevron-left fa-lg"></i></span>
      <span>Vorige</span>
    </div>
    <div class="info">
      <span>{{ from }}</span
      >&nbsp;&dash;&nbsp;<span>{{ to }}</span
      >&nbsp;&sol;&nbsp;<span>{{ total }}</span>
    </div>
    <div class="next action" @click="nextPage()">
      <span>Volgende</span>
      <span class="ms-3"><i class="fa-solid fa-chevron-right fa-lg"></i></span>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  pagination: {
    type: Object,
    required: true,
    default: () => {
      return {
        from: 1,
        to: 1,
        total: 0,
        page: 1,
        lastPage: 1,
      };
    },
  },
});

const from = computed(() => {
  return props.pagination.from;
});

const to = computed(() => {
  return props.pagination.to;
});

const total = computed(() => {
  return props.pagination.total;
});

const page = computed(() => {
  return props.pagination.current_page;
});

const lastPage = computed(() => {
  return props.pagination.last_page;
});

// TODO: Make pagination appear in url query

const router = useRouter();

const emit = defineEmits(["previous-page", "next-page"]);

const previousPage = () => {
  const currentQuery = router.currentRoute.value.query;

  if (page.value == 1) return;

  router.push({
    query: {
      ...currentQuery,
      page: page.value - 1,
    },
  });
  emit("previous-page");
};

const nextPage = () => {
  const currentQuery = router.currentRoute.value.query;

  if (page.value == lastPage.value) return;

  router.push({
    query: {
      ...currentQuery,
      page: page.value + 1,
    },
  });
  emit("next-page");
};
</script>
