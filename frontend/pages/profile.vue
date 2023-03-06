<template>
  <section class="dashboard__profile">
    <div class="profile__title">Profiel</div>
    <form class="profile__form">
      <div class="row">
        <div class="col-8">
          <p>
            Op deze pagina kan je je persoonlijke gegevens aanvullen en bewerken. We
            gebruiken deze gegevens om je te kunnen contacteren mocht dit nodig zijn. Deze
            gegevens zijn niet publiek zichtbaar voor andere gebruikers van de applicatie.
            Enkel medewerkers van de Gezinsbond hebben toegang tot deze gegevens.
          </p>
        </div>
        <div class="col-4 d-flex justify-content-end">
          <div>
            <button
              type="button"
              class="btn btn-outline-secondary me-2"
              @click="handleReset"
              :disabled="pending || loading"
            >
              Annuleren
            </button>
          </div>
          <div>
            <LoadingButton
              @click="updateProfile"
              :loading="loading"
              type="primary"
              :disabled="pending"
              >Opslaan</LoadingButton
            >
          </div>
        </div>
      </div>

      <hr class="my-4" />

      <div class="row">
        <div class="col-3">Naam*</div>
        <div class="col">
          <VTextInput name="firstname" placeholder="Voornaam" />
        </div>
        <div class="col">
          <VTextInput name="lastname" placeholder="Familienaam" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row mb-3">
        <div class="col-3">Emailadres</div>
        <div class="col-9">
          <VTextInput type="email" name="email" placeholder="Email" disabled />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row">
        <div class="col-3">Telefoon*</div>
        <div class="col-9">
          <VTextInput name="phoneNumber" placeholder="Telefoon" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row mb-3">
        <div class="col-3">Adres*</div>
        <div class="col">
          <VTextInput name="address" placeholder="Adres" />
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Gemeente*</div>
        <div class="col">
          <VTextInput name="city" placeholder="Gemeente" />
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-3">Postcode*</div>
        <div class="col">
          <VTextInput name="postalCode" placeholder="Postcode" />
        </div>
      </div>
      <hr class="my-4" />
      <div class="row mb-3">
        <div class="col-3">Lidnummer</div>
        <div class="col">
          <VTextInput
            name="memberNumber"
            placeholder="Lidnummer"
            hint="Lidnummer in het formaat xxx-xxx-xxx"
          />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <TheNotification />
        </div>
      </div>
    </form>
  </section>
</template>
<script setup>
import { object, string } from "yup";
import { useNotificationStore } from "~~/store/notification";
import { useForm } from "vee-validate";

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
const loading = ref(false);
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

const validationSchema = object({
  email: string().required().email().label("Email"),
  firstname: string().required().label("Voornaam"),
  lastname: string().required().label("Familienaam"),
  memberNumber: string()
    .matches(/^\d{3}-\d{3}-\d{3}$/, {
      message: "Ongeldig lidnummer. Het formaat is xxx-xxx-xxx.",
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
const { handleSubmit, handleReset } = useForm({
  initialValues: user,
  validationSchema,
});

const updateProfile = handleSubmit(async (values, actions) => {
  loading.value = true;
  const { data: resData } = await useApi("/api/auth/me", {
    method: "PUT",
    body: values,
  });
  const { data, message, status } = resData.value;
  notificationStore.message = message;
  notificationStore.status = status;
  if (status === "Success") {
    user.value = data;
    actions.resetForm();
  }
  loading.value = false;
});
</script>
