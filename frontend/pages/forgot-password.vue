<template>
  <section class="section__login">
    <div class="login__form">
      <div class="form__image">
        <img src="~/assets/img/tweedehandsbeurs.jpg" />
      </div>
      <form class="form__form" @submit="login">
        <div class="form__title">
          <h1 class="">Wachtwoord vergeten?</h1>
        </div>
        <p>Geen probleem. We sturen je instructies om je wachtwoord te veranderen.</p>
        <div class="form-group">
          <VTextInput name="email" placeholder="Jouw email" />
        </div>
        <LoadingButton
          @click="login"
          class="justify-content-center"
          type="primary"
          :loading="loading"
          >Wachtwoord resetten</LoadingButton
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

const notificationStore = useNotificationStore();

const initialValues = {
  email: "",
};

const { handleSubmit, setErrors } = useForm({
  initialValues,
});

const loading = ref(false);

const login = handleSubmit(async (values) => {
  loading.value = true;

  const { data, error } = await useCustomFetch("/api/auth/forgot-password/request", {
    method: "POST",
    body: values,
    initialCache: false,
  });

  if (error.value != null) {
    setErrors(error.value.data.errors);
    return;
  }

  notificationStore.addNotification(data.value.status, data.value.message);
  loading.value = false;
});
</script>
