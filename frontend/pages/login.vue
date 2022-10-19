<template>
  <section class="section__login">
    <div class="login__form">
      <div class="form__image">
        <img src="~/assets/img/clothes.jpg" />
      </div>
      <div class="form__form">
        <div class="form__title">
          <h1 class="">Welkom terug!</h1>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Jouw email" v-model="user.email" />
          <p v-if="fieldErrors.email">{{ fieldErrors.email }}</p>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="exampleInputPassword" placeholder="Jouw wachtwoord" v-model="user.password" />
          <p v-if="fieldErrors.password">{{ fieldErrors.password }}</p>
        </div>
        <div class="form-group">
          <input type="checkbox" class="form-checkbox" id="customCheck" />
          <label class="form-checkbox-label" for="customCheck">Onthoud mij</label>
        </div>
        <button class="btn btn-login" @click="login">Login</button>
        <div class="form__options">
          <div class="options__forgot">
            <NuxtLink class="small" to="/forgot-password"> Wachtwoord vergeten? </NuxtLink>
          </div>
          <div class="options__register"><NuxtLink class="small" to="/register"> Maak een account!</NuxtLink></div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
const user = ref({
  email: '',
  password: '',
});

const fieldErrors = ref({});

const login = async () => {
  console.log('login');

  try {
    const res = await useCustomFetch('http://localhost:8000/api/auth/login', {
      method: 'POST',
      body: user.value,
    });
  } catch (error) {
    const {
      data: { status, errors },
    } = error;

    fieldErrors.value = errors;
  }
};
</script>
