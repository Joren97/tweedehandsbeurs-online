<template>
  <section class="section__register">
    <div class="register__form">
      <div class="form__image">
        <img src="~/assets/img/clothes.jpg" />
      </div>

      <div class="form__form">
        <div class="form__title">
          <h1>Maak een account!</h1>
        </div>
        <form @submit="register">
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
              <VTextInput name="firstname" placeholder="Voornaam" />
            </div>
            <div class="col-sm-6">
              <VTextInput name="lastname" placeholder="Familienaam" />
            </div>
          </div>
          <div class="form-group">
            <VTextInput name="email" placeholder="Emailadres" />
          </div>
          <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
              <VTextInput name="password" placeholder="Wachtwoord" type="password" />
            </div>
            <div class="col-sm-6">
              <VTextInput
                name="confirmPassword"
                placeholder="Bevestig wachtwoord"
                type="password"
              />
            </div>
          </div>
          <LoadingButton
            @click="register"
            class="justify-content-center"
            type="primary"
            :loading="loading"
            >Registreer account</LoadingButton
          >
        </form>
        <div class="form__options">
          <div class="options__forgot">
            <NuxtLink class="small" to="/forgot-password">
              Wachtwoord vergeten?
            </NuxtLink>
          </div>
          <div class="options__login">
            Heb je al een account?
            <NuxtLink class="small" to="/login"> Meld je hier aan! </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
const user = ref({
  firstname: "",
  lastname: "",
  email: "",
  password: "",
  passwordConfirm: "",
});

const register = async () => {
  console.log(user.value);
};
</script>
