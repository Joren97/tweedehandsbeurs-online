<template>
  <header class="c-header">
    <div class="c-header__menu">
      <div class="menu__profile">
        <div class="profile__name">
          {{ username }}
        </div>
        <div class="profile__avatar">
          <img class="img-profile rounded-circle" src="~/assets/img/undraw_profile.svg"/>
        </div>
      </div>
    </div>
  </header>
</template>
<script setup>
import {useAuthStore} from '~~/store/auth';

const authStore = useAuthStore();
const username = computed(() => {
  if (!authStore.user) return 'No user';
  return authStore.user.firstname + ' ' + authStore.user.lastname;
});
</script>
