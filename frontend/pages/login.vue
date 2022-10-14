<template>
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welkom terug!</h1>
                  </div>
                  <div class="custom-form">
                    <div class="form-group">
                      <input
                        type="email"
                        class="form-control custom-form-control"
                        id="exampleInputEmail"
                        aria-describedby="emailHelp"
                        placeholder="Jouw email"
                        v-model="user.email"
                      />
                      <p v-if="fieldErrors.email">{{ fieldErrors.email }}</p>
                    </div>
                    <div class="form-group">
                      <input
                        type="password"
                        class="form-control custom-form-control"
                        id="exampleInputPassword"
                        placeholder="Jouw wachtwoord"
                        v-model="user.password"
                      />
                      <p v-if="fieldErrors.password">{{ fieldErrors.password }}</p>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="customCheck"
                          v-model="user.remember"
                        />
                        <label class="custom-control-label" for="customCheck"
                          >Remember Me</label
                        >
                      </div>
                    </div>
                    <button class="btn btn-primary custom-btm btn-block" @click="login">
                      Login
                    </button>
                  </div>
                  <hr />
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.html">Create an Account!</a>
                  </div>
                </div>
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
  email: "",
  password: "",
  remember: false,
});

const fieldErrors = ref({});

const login = async () => {
  console.log("login");

  try {
    const { data } = await useCustomFetch("http://localhost:8000/api/auth/login", {
      method: "POST",
      body: user.value,
    });

    let maxAge = null;
    if (data.remember) maxAge = 604800;
    const token = useCookie("apiToken", { maxAge });
    token.value = data.token;
    navigateTo("/");
  } catch (error) {
    const {
      data: { status, errors },
    } = error;

    fieldErrors.value = errors;
  }
};
</script>
