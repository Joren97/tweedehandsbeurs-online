<template>
  <section class="dashboard__profile">
    <div class="profile__title">Profiel</div>
    <div class="row">
      <div class="col-8">
        <p>
          Op deze pagina kan je je persoonlijke gegevens aanpassen. We gebruiken deze
          gegevens om je te kunnen contacteren mocht dit nodig zijn. Deze gegevens zijn
          niet publiek zichtbaar voor andere gebruikers van de applicatie. Enkel
          medewerkers van de Gezinsbond hebben toegang tot deze gegevens.
        </p>
      </div>
      <div class="col-4 d-flex justify-content-end">
        <div>
          <button type="button" class="btn btn-outline-secondary me-2">Annuleren</button>
        </div>
        <div>
          <LoadingButton @click="updateProfile" :loading="false" type="primary"
            >Opslaan</LoadingButton
          >
        </div>
      </div>
    </div>

    <hr class="my-4" />

    <VForm
      class="profile__form"
      @submit="updateProfile"
      :validation-schema="validationSchema"
      :initial-values="user"
      v-slot="{ meta: formMeta, errors: formErrors }"
    >
      <div class="row">
        <div class="col-3">Naam</div>
        <div class="col">
          <input class="form-control" placeholder="Voornaam" />
        </div>
        <div class="col">
          <input class="form-control" placeholder="Familienaam" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row">
        <div class="col-3">Emailadres</div>
        <div class="col-9">
          <VTextInput
            type="email"
            name="email"
            placeholder="Email"
            disabled
            class="mb-3"
          />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row">
        <div class="col-3">Telefoon</div>
        <div class="col-9">
          <VTextInput name="phoneNumber" placeholder="Telefoon" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row mb-3">
        <div class="col-3">Straat</div>
        <div class="col">
          <input class="form-control" placeholder="Straat" />
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Gemeente</div>
        <div class="col">
          <input class="form-control" placeholder="Gemeente" />
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Postcode</div>
        <div class="col">
          <input class="form-control" placeholder="Postcode" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row">
        <div class="col-3">Lidnummer</div>
        <div class="col">
          <input class="form-control" placeholder="Lidnummer" />
        </div>
      </div>
      <TheNotification />
    </VForm>
  </section>
</template>
<script setup>
import { object, string } from "yup";
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
  const { status, message, data } = await useApi("/api/auth/me", {
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
