<template>
  <form @submit="submit">
    {{ values }}
    {{ errors }}
    <div class="row mb-2">
      <div class="col">
        <label for="description" class="form-label">Beschrijving:</label>
        <VTextInput name="description" placeholder="Beschrijving" class="form-control" />
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <label for="priceId" class="form-label">Vraagprijs:</label>
        <VField name="priceId" as="select" v-model="selectedPriceId" class="form-select">
          <option :value="0">-- Selecteer een vraagprijs --</option>
          <option v-for="item in prices" :value="item.id">
            {{ toEuro(item.askingPrice) }}
          </option>
        </VField>
        <VErrorMessage name="priceId" as="div" class="invalid-feedback" />
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="selling-price" class="form-label">Verkoopprijs:</label>
        <select
          name="selling-price"
          id="selling-price"
          v-model="selectedPriceId"
          disabled
          class="form-select"
        >
          <option :value="0">-- Selecteer een vraagprijs --</option>
          <option v-for="item in prices" :value="item.id">
            {{ toEuro(item.sellingPrice) }}
          </option>
        </select>
      </div>
    </div>
  </form>
</template>
<script setup>
import { number, object, string } from "yup";
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
  priceId: number().min(1).required().label("Vraagprijs"),
});

const initialValues = {
  description: "",
  priceId: 0,
};

const { handleSubmit, handleReset, values, errors } = useForm({
  initialValues,
  validationSchema,
});

const prices = computed(() => {
  if (props.priceData == null) return [];
  return props.priceData.data;
});

const emit = defineEmits(["product-created"]);

const selectedPriceId = ref(0);
const fieldErrors = ref({});

const submit = handleSubmit(async (values, actions) => {
  const body = {
    ...values,
    productlistId: useRoute().params.id,
  };

  const { data: resData } = await useApi(`/api/product/me`, {
    method: "POST",
    body,
    initialCache: false,
  });

  const { data, message, status } = resData.value;

  if (status === "error") {
    fieldErrors.value = data.errors;
    return;
  }

  emit("product-created");

  actions.resetForm();
});

defineExpose({ submit, handleReset });
</script>
