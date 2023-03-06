<template>
  <div class="stat-card">
    <div class="stat-card__content">
      <p class="text-uppercase mb-1 text-muted">{{ title }}</p>
      <h2><i :class="textIcon"></i> {{ text }}</h2>
      <div>
        <span class="text-success text-bold me-1"
          ><i class="fa fa-arrow-up"></i> +5%</span
        >
        <span class="text-muted">vs last 28 days</span>
      </div>
    </div>
    <div class="stat-card__icon" :class="`stat-card__icon--${color}`">
      <div class="stat-card__icon-circle">
        <i :class="icon"></i>
      </div>
    </div>
  </div>
</template>
<script setup>
defineProps({
  title: {
    type: String,
    required: false,
    default: "",
  },
  icon: {
    type: String,
    required: false,
    default: "",
  },
  text: {
    type: String,
    required: false,
    default: "",
  },
  textIcon: {
    type: String,
    required: false,
    default: "",
  },
  color: {
    type: String,
    required: false,
    default: "primary",
  },
});
</script>
