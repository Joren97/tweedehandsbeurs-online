<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Profile</template>
    </LayoutPageHeading>
    <VForm
      @submit="updateProfile"
      :validation-schema="validationSchema"
      :initial-values="user"
      v-slot="{ meta: formMeta, errors: formErrors }"
    >
      <VTextInput type="email" label="Email" name="email" placeholder="Email" disabled />
      <VTextInput label="Voornaam" name="firstname" placeholder="John" />
      <VTextInput label="Familienaam" name="lastname" placeholder="Doe" />
      <VTextInput label="Lidnummer" name="memberNumber" />
      <VTextInput label="Telefoon" name="phoneNumber" />
      <VTextInput label="Adres" name="address" />
      <VTextInput label="Gemeente" name="city" />
      <VTextInput label="Postcode" name="postalCode" />
      <button class="btn btn-primary" type="submit" :disabled="!formMeta.valid">
        Submit
      </button>
    </VForm>
  </div>
</template>
<script setup>
import { object, string } from "yup";
import _ from "lodash";
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
  title: "Profiel",
});

const user = ref({});

const { data: userData, pending, error, refresh } = await myLazyFetch(
  () => "/api/auth/userinfo",
  {
    initialCache: false,
  }
);

watch(userData, (newVal) => {
  user.value = newVal.data;
});

const updateProfile = async (values, actions) => {
  const { data, pending, error } = await myAsyncData(
    () => "/api/auth/me",
    {
      method: "PUT",
      body: values,
    },
    {
      initialCache: false,
    }
  );
  refresh();
  actions.resetForm();
};

const validationSchema = object({
  email: string().required().email().label("Email"),
  firstname: string().required().label("Voornaam"),
  lastname: string().required().label("Familienaam"),
  memberNumber: string()
    .matches(/^\d{3}-\d{3}-\d{3}$/, {
      message: "Ongeldig lidnummer.",
      excludeEmptyString: true,
    })
    .nullable()
    .label("Lidnummer"),
  phoneNumber: string().required().matches(/^\d*$/, {
    message: "Telefoon mag enkel cijfers bevatten.",
    excludeEmptyString: true,
  }),
  address: string().required(),
  city: string().required(),
  postalCode: string()
    .required()
    .matches(/^\d{4}$/, {
      message: "Ongeldige postcode.",
    }),
});
</script>
