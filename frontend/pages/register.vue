<template>
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Maak een account!</h1>
              </div>
              <div class="custom-form">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input
                      type="text"
                      class="form-control custom-form-control"
                      id="exampleFirstName"
                      placeholder="Voornaam"
                      v-model="user.firstname"
                    />
                  </div>
                  <div class="col-sm-6">
                    <input
                      type="text"
                      class="form-control custom-form-control"
                      id="exampleLastName"
                      placeholder="Naam"
                      v-model="user.lastname"
                    />
                  </div>
                </div>
                <div class="form-group">
                  <input
                    type="email"
                    class="form-control custom-form-control"
                    id="exampleInputEmail"
                    placeholder="Emailadres"
                    v-model="user.email"
                  />
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input
                      type="password"
                      class="form-control custom-form-control"
                      id="exampleInputPassword"
                      placeholder="Wachtwoord"
                      v-model="user.password"
                    />
                  </div>
                  <div class="col-sm-6">
                    <input
                      type="password"
                      class="form-control custom-form-control"
                      id="exampleRepeatPassword"
                      placeholder="Bevestig wachtwoord"
                      v-model="user.passwordConfirm"
                    />
                  </div>
                </div>
                <button class="btn btn-primary custom-btm btn-block" @click="register">
                  Registreer account
                </button>
              </div>
              <hr />
              <div class="text-center">
                <NuxtLink class="small" to="/forgot-password">
                  Wachtwoord vergeten?
                </NuxtLink>
              </div>
              <div class="text-center">
                <NuxtLink class="small" to="/login">
                  Heb je al een account? Meld je hier aan!
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
