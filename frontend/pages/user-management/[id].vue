<template>
  <section class="section__user-management-detail">
    <div class="title">Joren Synaeve</div>
    Gebruikersinfo
  </section>
</template>
<script>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});
</script>
