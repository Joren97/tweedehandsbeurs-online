<template>
  <div>
    <label class="form-label">{{ label }}</label>
    <VField :name="name" v-slot="{ field, meta, errors }">
      <input
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
    </VField>
  </div>
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
  label: {
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
