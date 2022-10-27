<template>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newListModalLabel">Nieuw product toevoegen</h1>
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
          id="dismiss-button"
        >
          Sluiten
        </button>
        <button type="button" class="btn btn-primary" @click="submitProduct">
          Product toevoegen
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
});

const prices = computed(() => {
  if (props.priceData == null) return [];
  return props.priceData.data;
});

const emit = defineEmits(["product-created"]);

const description = ref("");
const selectedPriceId = ref(0);
const fieldErrors = ref({});

const sellingPrice = computed(() => {
  if (selectedPriceId.value === 0) return "-- Selecteer een vraagprijs --";
  const price = prices.value.find((p) => p.id === selectedPriceId.value);
  return toEuro(price.sellingPrice);
});

const submitProduct = async () => {
  const body = {
    description: description.value,
    priceId: selectedPriceId.value,
    productlistId: useRoute().params.id,
  };

  const { data, pending, error } = await useCustomFetch(`/api/product/me`, {
    method: "POST",
    body,
    initialCache: false,
  });

  if (error.value != null) {
    console.log(error.value);
    fieldErrors.value = error.value.data.errors;
    return;
  }

  // Hide the bootstrap modal
  document.getElementById("dismiss-button").click();
  // Clear fields
  description.value = "";
  selectedPriceId.value = 0;
  // Emit to parent
  emit("product-created");
};
</script>
