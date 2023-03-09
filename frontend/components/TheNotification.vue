<template>
  <div class="alert alert-dismissible fade show" role="alert" :class="type">
    {{ notificationStore.message }}
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="alert"
      aria-label="Close"
      @click="clear"
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

const clear = () => {
  notificationStore.message = "";
};

watch(
  () => notificationStore.message,
  (newVal) => {
    setTimeout(() => {
      notificationStore.message = "";
    }, 3000);
  }
);
</script>
