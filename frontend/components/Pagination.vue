<template>
  <div>
    <span>{{ from }}</span
    >&nbsp;&dash;&nbsp;<span>{{ to }}</span
    >&nbsp;&sol;&nbsp;<span>{{ total }}</span>
    <button
      class="btn btn-primary"
      type="button"
      :disabled="page == 1"
      @click="previousPage()"
    >
      <i class="fas fa-chevron-left"></i>
    </button>
    <button
      class="btn btn-primary"
      type="button"
      :disabled="page == lastPage"
      @click="nextPage()"
    >
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>
</template>
<script setup>
const props = defineProps({
  from: {
    type: Number,
    required: true,
    default: 1,
  },
  to: {
    type: Number,
    required: true,
    default: 1,
  },
  total: {
    type: Number,
    required: true,
    default: 0,
  },
  page: {
    type: Number,
    required: true,
    default: 1,
  },
  lastPage: {
    type: Number,
    required: true,
    default: 1,
  },
});

// TODO: Make pagination appear in url query

const router = useRouter();

const emit = defineEmits(["previous-page", "next-page"]);

const previousPage = () => {
  router.push({
    query: {
      page: props.page - 1,
    },
  });
  emit("previous-page");
};

const nextPage = () => {
  router.push({
    query: {
      page: props.page + 1,
    },
  });
  emit("next-page");
};
</script>
