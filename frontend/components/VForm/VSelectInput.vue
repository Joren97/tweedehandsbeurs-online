<template>
  <VField :name="name" v-slot="{ field, meta, errors }">
    <select
      :id="name"
      :disabled="disabled"
      v-bind="field"
      class="form-select"
      :class="{
        'is-valid': meta.valid && meta.touched,
        'is-invalid': !meta.valid && meta.touched,
      }"
      :placeholder="placeholder"
    >
      <slot></slot>
    </select>

    <VErrorMessage :name="name" as="div" class="invalid-feedback" />
  </VField>
</template>

<script setup>
const props = defineProps({
  type: {
    type: String,
    default: "text",
  },
  name: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: "",
  },
  leftIcon: {
    type: String,
    default: "",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});
</script>

<style></style>
