<template>
  <div class="c-modal" id="add-product-modal" :class="{ visible: visible }">
    <div class="modal__content">
      <div class="modal__header">
        <div class="modal__title"><slot name="title"></slot></div>
        <button title="Close" class="btn-close modal__close" @click="close" />
      </div>
      <div class="modal__body"><slot name="content"></slot></div>
      <div class="modal__footer"><slot name="footer"></slot></div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
    default: false,
  },
});
</script>
