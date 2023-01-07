<template>
  <div class="alert alert-dismissible fade show" role="alert" :class="type">
    {{ notificationStore.message }}
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="alert"
      aria-label="Close"
    ></button>
  </div>
</template>
<script setup>
import { useNotificationStore } from "~~/store/notification";
const notificationStore = useNotificationStore();

const type = computed(() => {
  if (notificationStore.message == "") return "d-none";
  if (notificationStore.status == "Success") return "alert-success";
  if (notificationStore.status == "Error") return "alert-danger";
});

watch(
  () => notificationStore.message,
  (newVal) => {
    console.log("New value in the store", newVal);
    setTimeout(() => {
      notificationStore.message = "";
    }, 3000);
  }
);
</script>
