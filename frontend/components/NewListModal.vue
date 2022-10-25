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
        <input type="text" class="form-control" id="list-number" v-model="listNumber" />
        <p v-if="fieldErrors.listNumber">{{ fieldErrors.listNumber }}</p>
        <label for="member-number" class="col-form-label">Lidnummer:</label>
        <input
          type="text"
          class="form-control"
          id="member-number"
          v-model="memberNumber"
        />
        <p v-if="fieldErrors.memberNumber">{{ fieldErrors.memberNumber }}</p>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
          id="dismiss-button"
        >
          Sluiten
        </button>
        <button type="button" class="btn btn-primary" @click="submitList">
          Lijst aanmaken
        </button>
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

const emit = defineEmits(["list-created"]);

const listNumber = ref("");
const memberNumber = ref("");
const fieldErrors = ref({});

const editionName = computed(() => {
  if (!props.activeEdition) return "";
  return props.activeEdition.year + " - " + props.activeEdition.name;
});

const submitList = async () => {
  const body = {
    editionId: props.activeEdition.id,
    listNumber: listNumber.value,
    memberNumber: memberNumber.value,
  };
  const { data, pending, error } = await useCustomFetch(`/api/productlist/me`, {
    method: "POST",
    body,
    initialCache: false,
  });

  if (error.value != null) {
    fieldErrors.value = error.value.data.errors;
    return;
  }

  // Hide the bootstrap modal
  document.getElementById("dismiss-button").click();
  // Clear fields
  listNumber.value = "";
  memberNumber.value = "";
  // Emit to parent
  emit("list-created");
};
</script>
