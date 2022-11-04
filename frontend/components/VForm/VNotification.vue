<template>
  <div>
    <div class="alert alert-dismissible fade show" role="alert" :class="type">
      {{ alertMessage }}
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
      ></button>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  message: {
    type: String,
    default: "",
  },
  type: {
    type: String,
    default: "",
  },
});

const type = computed(() => {
  if (alertMessage.value == "") return "d-none";
  if (props.type == "Success") return "alert-success";
  if (props.type == "Error") return "alert-danger";
});

const alertMessage = ref("");

watch(
  () => props.message,
  (newVal) => {
    alertMessage.value = newVal;
    setTimeout(() => {
      alertMessage.value = "";
    }, 3000);
  }
);
</script>
