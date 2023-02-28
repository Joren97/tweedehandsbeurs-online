<template>
  <section class="section__login">
    <div class="login__form">
      <div class="form__image">
        <img src="~/assets/img/clothes.jpg" />
      </div>
      <form class="form__form" @submit="login">
        <div class="form__title">
          <h1 class="">Welkom terug!</h1>
        </div>
        <div class="form-group">
          <VTextInput name="email" placeholder="Jouw email" />
        </div>
        <div class="form-group">
          <VTextInput name="password" placeholder="Jouw wachtwoord" type="password" />
        </div>
        <Field
          v-slot="{ field }"
          name="remember"
          type="checkbox"
          :value="true"
          as="div"
          class="form-check mb-3"
        >
          <input
            class="form-check-input"
            type="checkbox"
            name="remember"
            v-bind="field"
            :value="true"
          />
          <label for="remember" class="form-check-label">Onthoud mij</label>
        </Field>
        <LoadingButton
          @click="login"
          class="justify-content-center"
          type="primary"
          :loading="loading"
          >Aanmelden</LoadingButton
        >
        <button type="submit" class="d-none">Submit</button>
        <div class="form__options">
          <div class="options__forgot">
            <NuxtLink class="small" to="/forgot-password">
              Wachtwoord vergeten?
            </NuxtLink>
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
import { useAuthStore } from "~~/store/auth";
import { Field, useForm } from "vee-validate";

const authStore = useAuthStore();

const initialValues = {
  email: "",
  password: "",
  remember: false,
};

const { handleSubmit, setErrors } = useForm({
  initialValues,
});

const loading = ref(false);

const login = handleSubmit(async (values) => {
  loading.value = true;

  const { data, error } = await useCustomFetch("/api/auth/login", {
    method: "POST",
    body: values,
    initialCache: false,
  });

  loading.value = false;

  if (error.value != null) {
    setErrors(error.value.data.errors);
    return;
  }

  // Set token
  let maxAge = null;
  const user = data.value.data;
  if (data.value.data.remember) maxAge = 604800;
  const token = useCookie("apiToken", { maxAge });
  token.value = user.token;
  // Set the userinfo in the store
  authStore.user = user;
  navigateTo("/");
});

useHead({
  title: "Aanmelden",
});
</script>
