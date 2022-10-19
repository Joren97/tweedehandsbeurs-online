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
        <div class="form-group row">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <input type="text" class="form-control" id="exampleFirstName" placeholder="Voornaam" v-model="user.firstname" />
          </div>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="exampleLastName" placeholder="Naam" v-model="user.lastname" />
          </div>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" id="exampleInputEmail" placeholder="Emailadres" v-model="user.email" />
        </div>
        <div class="form-group row">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <input type="password" class="form-control" id="exampleInputPassword" placeholder="Wachtwoord" v-model="user.password" />
          </div>
          <div class="col-sm-6">
            <input type="password" class="form-control" id="exampleRepeatPassword" placeholder="Bevestig wachtwoord" v-model="user.passwordConfirm" />
          </div>
        </div>
        <button class="btn btn-register" @click="register">Registreer account</button>
        <div class="form__options">
          <div class="options__forgot">
            <NuxtLink class="small" to="/forgot-password"> Wachtwoord vergeten? </NuxtLink>
          </div>
          <div class="options__login">Heb je al een account? <NuxtLink class="small" to="/login"> Meld je hier aan! </NuxtLink></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const user = ref({
  firstname: '',
  lastname: '',
  email: '',
  password: '',
  passwordConfirm: '',
});

const register = async () => {
  console.log(user.value);
};
</script>
