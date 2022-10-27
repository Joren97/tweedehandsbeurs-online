<template>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editProductModal">Product bewerken</h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <label for="description" class="col-form-label">Beschrijving:</label>
        <input type="text" class="form-control" id="description" v-model="description" />
        <p v-if="fieldErrors.description">{{ fieldErrors.description }}</p>

        <label for="asking-price" class="col-form-label">Vraagprijs:</label>
        <select class="form-select" aria-label="Vraagprijs" v-model="selectedPriceId">
          <option selected>-- Selecteer een vraagprijs --</option>
          <option v-for="item in prices" :value="item.id">
            {{ toEuro(item.askingPrice) }}
          </option>
        </select>
        <p v-if="fieldErrors.price">{{ fieldErrors.price }}</p>

        <label for="selling-price" class="col-form-label">Verkoopprijs:</label>
        <input
          type="text"
          class="form-control"
          id="selling-price"
          disabled
          :value="sellingPrice"
        />
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-bs-dismiss="modal"
          id="dismiss-button-edit"
        >
          Sluiten
        </button>
        <button type="button" class="btn btn-primary" @click="updateProduct">
          Wijzigigen opslaan
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  priceData: {
    type: [Object, null],
    required: true,
    default: { data: [] },
  },
  productToEdit: {
    type: [Object, null],
    required: true,
    default: null,
  },
});

const fieldErrors = ref({});
const updatedDescription = ref("");
const updatedSelectedPriceId = ref(0);

watch(
  () => props.productToEdit,
  (newVal) => {
    fieldErrors.value = {};
    updatedDescription.value = newVal.description;
    updatedSelectedPriceId.value = newVal.priceId;
  }
);

const prices = computed(() => {
  if (props.priceData == null) return [];
  return props.priceData.data;
});

const emit = defineEmits(["product-updated"]);

const description = computed({
  get: () => {
    if (props.productToEdit == null) return "";
    if (updatedDescription.value != "") return updatedDescription.value;
    return props.productToEdit.description;
  },
  set: (val) => (updatedDescription.value = val),
});

const selectedPriceId = computed({
  get: () => {
    if (props.productToEdit == null) return 0;
    if (updatedSelectedPriceId.value != 0) return updatedSelectedPriceId.value;
    return props.productToEdit.priceId;
  },
  set: (val) => (updatedSelectedPriceId.value = val),
});

const sellingPrice = computed(() => {
  if (updatedSelectedPriceId.value === 0 && selectedPriceId.value === 0)
    return "-- Selecteer een vraagprijs --";
  if (updatedSelectedPriceId.value === 0 && selectedPriceId.value !== 0)
    return toEuro(prices.value.find((p) => p.id === selectedPriceId.value).sellingPrice);
  if (updatedSelectedPriceId.value !== 0)
    return toEuro(
      prices.value.find((p) => p.id === updatedSelectedPriceId.value).sellingPrice
    );
});

const updateProduct = async () => {
  const body = {
    description: updatedDescription.value,
    priceId: updatedSelectedPriceId.value,
  };

  const { data, pending, error } = await useCustomFetch(
    `/api/product/me/${props.productToEdit.id}`,
    {
      method: "PUT",
      body,
      initialCache: false,
    }
  );

  if (error.value != null) {
    console.log(error.value);
    fieldErrors.value = error.value.data.errors;
    return;
  }

  // Hide the bootstrap modal
  document.getElementById("dismiss-button-edit").click();
  // Emit to parent
  emit("product-updated");
};
</script>
