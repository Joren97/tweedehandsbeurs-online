<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Profiel</template>
    </LayoutPageHeading>
    <p>
      Op deze pagina kan je je persoonlijke gegevens aanpassen. We gebruiken deze gegevens
      om je te kunnen contacteren mocht dit nodig zijn. Deze gegevens zijn niet publiek
      zichtbaar voor andere gebruikers van de applicatie. Enkel medewerkers van de
      Gezinsbond hebben toegang tot deze gegevens.
    </p>
    <VForm
      @submit="updateProfile"
      :validation-schema="validationSchema"
      :initial-values="user"
      v-slot="{ meta: formMeta, errors: formErrors }"
    >
      <div class="row">
        <div class="col-12">
          <p class="fw-bold">Emailadres</p>
          <VTextInput
            type="email"
            label="Email"
            name="email"
            placeholder="Email"
            disabled
          />
        </div>
      </div>
      <div class="row">
        <div class="col-12"><p class="fw-bold">Volledige naam</p></div>
      </div>
      <div class="row">
        <div class="col-6">
          <VTextInput label="Voornaam" name="firstname" placeholder="John" />
        </div>
        <div class="col-6">
          <VTextInput label="Familienaam" name="lastname" placeholder="Doe" />
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <p class="fw-bold">Adresgegevens</p>
        </div>
        <div class="col-6">
          <VTextInput label="Gemeente" name="city" />
        </div>
        <div class="col-6">
          <VTextInput label="Postcode" name="postalCode" />
        </div>
        <div class="col-12">
          <VTextInput label="Straat + nummer" name="address" />
        </div>
      </div>
      <div class="row">
        <div class="col-12"><p class="fw-bold">Extra info</p></div>
        <div class="col-6">
          <VTextInput label="Lidnummer" name="memberNumber" />
        </div>
        <div class="col-6">
          <VTextInput label="Telefoon" name="phoneNumber" />
        </div>
      </div>
      <button class="btn btn-primary" type="submit" :disabled="!formMeta.valid">
        Submit
      </button>
      <TheNotification :message="alertMessage" :type="alertType" />
    </VForm>
  </div>
</template>
<script setup>
import { object, string } from "yup";
import _ from "lodash";
import { useNotificationStore } from "~~/store/notification";
const notificationStore = useNotificationStore();
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
  title: "Profiel",
});

useHead({
  title: "Profiel",
});

const user = ref({});
const alertMessage = ref("");
const alertType = ref("");

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
  const { status, message, data } = await useAPI("/api/auth/me", {
    method: "PUT",
    body: values,
  });

  notificationStore.message = message;
  notificationStore.status = status;

  if (status === "Success") {
    user.value = data;
    actions.resetForm();
  }
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
