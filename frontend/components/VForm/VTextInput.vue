<template>
  <VField :name="name" v-slot="{ field, meta, errors }">
    <input
      :id="name"
      :disabled="disabled"
      v-bind="field"
      class="form-control"
      :class="{
        'is-valid': meta.valid && meta.touched,
        'is-invalid': !meta.valid && meta.touched,
      }"
      :placeholder="placeholder"
      :type="type"
    />
    <VErrorMessage :name="name" as="div" class="invalid-feedback" />
    <div class="form-text" v-if="hint">{{ hint }}</div>
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
  hint: {
    type: String,
    default: null,
  },
});
</script>

<style></style>
