<template>
  <span v-if="value"><i class="fa-regular fa-square-check fa-lg"></i></span>
  <span v-else><i class="fa-regular fa-square fa-lg"></i></span>
</template>
<script setup>
const props = defineProps({
  value: {
    type: Boolean,
    required: true,
    default: false,
  },
});
</script>
