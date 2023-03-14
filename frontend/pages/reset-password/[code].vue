<template>
  <section class="section__login">
    <div class="login__form">
      <div class="form__image">
        <img src="~/assets/img/tweedehandsbeurs.jpg" />
      </div>
      <form class="form__form" @submit="reset">
        <div class="form__title">
          <h1 class="">Nieuw wachtwoord instellen</h1>
        </div>
        <p>Gelieve een nieuw wachtwoord te kiezen.</p>
        <div class="form-group">
          <VTextInput
            name="password"
            placeholder="Jouw nieuwe wachtwoord"
            type="password"
          />
        </div>
        <div class="form-group">
          <VTextInput
            name="confirmPassword"
            placeholder="Bevestig je nieuwe wachtwoord"
            type="password"
          />
        </div>
        <LoadingButton
          @click="reset"
          class="justify-content-center"
          type="primary"
          :loading="loading"
          >Nieuw wachtwoord bevestigen</LoadingButton
        >

        <TheNotification class="mt-3" />
        <button type="submit" class="d-none">Submit</button>
        <div class="form__options">
          <div class="options__login">
            Heb je al een account?
            <NuxtLink class="small" to="/login"> Meld je hier aan! </NuxtLink>
          </div>
          <div class="options__register">
            <NuxtLink class="small" to="/register"> Maak een account!</NuxtLink>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>
<script setup>
import { useNotificationStore } from "~~/store/notification";
import { useForm } from "vee-validate";
import { object, string, ref as yupRef } from "yup";

const notificationStore = useNotificationStore();

const initialValues = {
  password: "",
  confirmPassword: "",
  token: useRoute().params.code,
};

const validationSchema = object({
  password: string().required(),
  confirmPassword: string()
    .required()
    .oneOf([yupRef("password"), null], "Wachtwoorden komen niet overeen"),
});

const { handleSubmit, setErrors } = useForm({
  initialValues,
  validationSchema,
});

const loading = ref(false);

const reset = handleSubmit(async (values) => {
  loading.value = true;

  const { data, error } = await useCustomFetch("/api/auth/forgot-password/reset", {
    method: "POST",
    body: values,
    initialCache: false,
  });

  if (error.value != null) {
    notificationStore.addNotification("Error", error.value.data.message);
    loading.value = false;
    return;
  }

  notificationStore.addNotification(data.value.status, data.value.message);
  loading.value = false;
});
</script>
