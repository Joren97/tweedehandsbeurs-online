<template>
  <form @submit="submit">
    {{ meta }}
    <div class="row mb-2">
      <div class="col">
        <label for="listNumber" class="form-label">Lijstnummer*</label>
        <VTextInput name="listNumber" placeholder="Lijstnummer" class="form-control" />
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <label for="memberNumber" class="form-label">Lidnummer voor deze lijst</label>
        <VTextInput name="memberNumber" placeholder="Lidnummer" class="form-control" />
      </div>
    </div>
    <div class="row mb-2">
      <div class="col">
        <label for="edition" class="form-label">Editie</label>
        <input class="form-control" type="text" disabled :value="editionName" />
      </div>
    </div>
  </form>
</template>
<script setup>
import { object, string } from "yup";
import { useForm } from "vee-validate";

const props = defineProps({
  activeEdition: {
    type: [Object, null],
    required: true,
    default: null,
  },
});

const validationSchema = object({
  listNumber: string().required().label("Lijstnummer").matches(/^\d*$/, {
    message: "Lijstnummer mag enkel cijfers bevatten.",
    excludeEmptyString: true,
  }),
  memberNumber: string()
    .matches(/^\d{3}-\d{3}-\d{3}$/, {
      message: "Ongeldig lidnummer.",
      excludeEmptyString: true,
    })
    .nullable()
    .label("Lidnummer"),
});

const initialValues = {
  listNumber: "",
  memberNumber: "",
};

const { handleSubmit, handleReset, setErrors, meta } = useForm({
  validationSchema,
  initialValues,
});

const editionName = computed(() => {
  if (!props.activeEdition) return "";
  return props.activeEdition.year + " - " + props.activeEdition.name;
});

const emit = defineEmits(["list-created", "submit"]);

const submit = handleSubmit(async (values) => {
  emit("submit", values);
});

defineExpose({ submit, handleReset, setErrors });
</script>
