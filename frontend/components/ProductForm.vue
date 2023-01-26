<template>
  <form @submit="submit">
    <div class="row mb-2">
      <div class="col">
        <label for="description" class="form-label">Beschrijving:</label>
        <VTextInput name="description" placeholder="Beschrijving" class="form-control" />
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <label for="priceId" class="form-label">Vraagprijs:</label>
        <VSelectInput name="priceId">
          <option value="">
            &dash;&dash;&nbsp;Selecteer een vraagprijs&nbsp;&dash;&dash;
          </option>
          <option v-for="item in prices" :value="item.id">
            {{ toEuro(item.askingPrice) }}
          </option>
        </VSelectInput>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <label for="selling-price" class="form-label">Verkoopprijs:</label>
        <select
          name="selling-price"
          id="selling-price"
          v-model="values.priceId"
          disabled
          class="form-control"
        >
          <option value=""></option>
          <option v-for="item in prices" :value="item.id">
            {{ toEuro(item.sellingPrice) }}
          </option>
        </select>
      </div>
    </div>
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
  initialData: {
    type: Object,
    required: false,
    default: () => ({}),
  },
});

watch(
  () => props.initialData,
  (value) => {
    if (value) {
      setValues(value);
    }
  }
);

const validationSchema = object({
  description: string().required().label("Beschrijving"),
  priceId: string().ensure().required().label("Vraagprijs"),
});

const initialValues = {
  description: "",
  priceId: "",
};

const { handleSubmit, handleReset, values, setValues } = useForm({
  initialValues,
  validationSchema,
});

const prices = computed(() => {
  if (props.priceData == null) return [];
  return props.priceData.data;
});

const emit = defineEmits(["submit"]);

const submit = handleSubmit(async (values) => {
  emit("submit", values);
});

defineExpose({ submit, handleReset });
</script>
