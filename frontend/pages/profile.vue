<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Profile</template>
    </LayoutPageHeading>
    <form @submit.prevent="updateProfile">
      <div class="form-group">
        <label for="email">Email</label>
        <input
          disabled
          type="email"
          class="form-control"
          id="email"
          aria-describedby="emailHelp"
          placeholder="Email"
          v-model="user.email"
        />
      </div>
      <div class="form-group">
        <label for="firstname">Voornaam</label>
        <input
          class="form-control"
          id="firstname"
          placeholder="Voornaam"
          v-model="user.firstname"
        />
      </div>
      <div class="form-group">
        <label for="lastname">Achternaam</label>
        <input
          class="form-control"
          id="lastname"
          placeholder="Achternaam"
          v-model="user.lastname"
        />
      </div>
      <div class="form-group">
        <label for="member-number">Lidnummer</label>
        <input
          class="form-control"
          id="member-number"
          placeholder="Lidnummer"
          v-model="user.memberNumber"
        />
      </div>
      <div class="form-group">
        <label for="phone-number">Telefoon</label>
        <input
          class="form-control"
          id="phone-number"
          placeholder="Telefoon"
          v-model="user.phoneNumber"
        />
      </div>
      <div class="form-group">
        <label for="address">Adres</label>
        <input
          class="form-control"
          id="address"
          placeholder="Adres"
          v-model="user.address"
        />
      </div>
      <div class="form-group">
        <label for="city">Gemeente</label>
        <input
          class="form-control"
          id="city"
          placeholder="Gemeente"
          v-model="user.city"
        />
      </div>
      <div class="form-group">
        <label for="postal-code">Postcode</label>
        <input
          class="form-control"
          id="postal-code"
          placeholder="Postcode"
          v-model="user.postalCode"
        />
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</template>
<script setup>
import _ from "lodash";
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
  title: "Profiel",
});

const user = ref({});

const { data: userData, pending, error } = await myLazyFetch(() => "/api/auth/userinfo", {
  initialCache: false,
});

watch(userData, (newVal) => {
  user.value = newVal.data;
});

const updateProfile = async () => {
  console.log(user.value);
  const { data, pending, error } = await myAsyncData(() => "/api/auth/me", {
    method: "PUT",
    body: user.value,
  });
};
</script>
