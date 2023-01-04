<template>
  <button type="button" class="btn btn-loading" :class="computedClass">
    <span class="btn-text"><slot></slot></span>
    <span
      class="spinner-border spinner-border-sm"
      role="status"
      aria-hidden="true"
    ></span>
  </button>
</template>
<script setup>
const props = defineProps({
  type: {
    type: String,
    default: "",
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

const computedClass = computed(() => {
  let type = "";

  if (props.type != "") {
    type = " btn-" + props.type;
  }

  if (props.loading) {
    type += " loading";
  }

  return type;
});
</script>
