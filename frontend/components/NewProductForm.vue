<template>
  <form>
    {{ selectedPriceId }}
    {{ selectedPriceId == "" }}
    <label for="description">Beschrijving:</label>
    <VTextInput name="description" placeholder="Beschrijving" />

    <label for="asking-price" class="col-form-label">Vraagprijs:</label>
    <VField name="priceId" as="select" v-model="selectedPriceId">
      <option value="0">-- Selecteer een vraagprijs --</option>
      <option v-for="item in prices" :value="item.id">
        {{ toEuro(item.askingPrice) }}
      </option>
    </VField>
    <VErrorMessage name="priceId" as="div" class="invalid-feedback" />
    <VField name="priceId" as="select" v-model="selectedPriceId">
      <option value="0">-- Selecteer een vraagprijs --</option>
      <option v-for="item in prices" :value="item.id">
        {{ toEuro(item.askingPrice) }}
      </option>
    </VField>
    <select class="form-select" aria-label="Vraagprijs" v-model="selectedPriceId">
      <option value="0">-- Selecteer een vraagprijs --</option>
      <option v-for="item in prices" :value="item.id">
        {{ toEuro(item.sellingPrice) }}
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
  </form>
</template>
<script setup>
import { object, string } from "yup";
import { useForm } from "vee-validate";

const props = defineProps({
  priceData: {
    type: [Object, null],
    required: true,
    default: { data: [] },
  },
});

const validationSchema = object({
  description: string().required().label("Beschrijving"),
  priceId: string().required().label("Vraagprijs"),
});

const { handleSubmit, handleReset, meta } = useForm({
  validationSchema,
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
  if (!selectedPriceId.value) return "-- Selecteer een vraagprijs --";
  if (selectedPriceId.value == 0 || selectedPriceId.value == "")
    return "-- Selecteer een vraagprijs --";
  const price = prices.value.find((p) => p.id === selectedPriceId.value);
  return toEuro(price.sellingPrice);
});

const submit = async () => {
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
  // Clear fields
  description.value = "";
  selectedPriceId.value = 0;
  // Emit to parent
  emit("product-created");
};

defineExpose({ submit });
</script>
