<template>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newListModalLabel">Nieuwe lijst toevoegen</h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <label for="edition" class="col-form-label">Editie:</label>
        <input
          type="text"
          class="form-control"
          id="edition"
          disabled
          :value="editionName"
        />
        <label for="list-number" class="col-form-label">Lijstnummer:</label>
        <input type="text" class="form-control" id="list-number" />
        <label for="member-number" class="col-form-label">Lidnummer:</label>
        <input type="text" class="form-control" id="member-number" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Sluiten
        </button>
        <button type="button" class="btn btn-primary">Lijst aanmaken</button>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  activeEdition: {
    type: Object,
    required: true,
    default: null,
  },
});

const editionName = computed(() => {
  if (!props.activeEdition) return "";
  return props.activeEdition.year + " - " + props.activeEdition.name;
});
</script>
