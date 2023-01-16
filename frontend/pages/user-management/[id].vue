<template>
  <section class="section__user-management-detail">
    <div class="title">Gebruikersinfo</div>
    <div class="row">
      <div class="subtitle">Huidige editie</div>
      <div class="col-3">
        <div class="list__information">
          <div class="information__title">
            <span>{{ user.firstname }} {{ user.lastname }}</span>
          </div>
          <div class="information__content">
            <div class="information__item mb-2">
              <span class="item__title">Email</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.email) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Lidnummer</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.memberNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Telefoon</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(user.phoneNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Adres</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ formatAdress(user) }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="list__information mb-3">
          <div class="information__title">
            <span v-if="userPending" class="placeholder col-4"></span>
            <span v-else>Lijstoverzicht</span>
          </div>
          <div
            class="information__content placeholder-glow"
            v-for="item in user.productlists"
            :key="item.id"
          >
            <div class="information__item mb-2">
              <span class="item__title">Lijstnummer</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(item.listNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Lidnummer</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ emptyCheck(item.memberNumber) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Bevestigd</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="item.isUserConfirmed" /></span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Gevalideerd</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="item.isEmployeeValidated" /></span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Opbrengst uitbetaald</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else><YesNoIcon :value="item.isPaidToUser" /></span>
            </div>
            <div class="information__item">
              <span class="item__title">Opbrengst</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ toEuro(item.userProfit) }}</span>
            </div>
            <hr />
          </div>
        </div>
        <p>{{ user.productlists }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <p class="subtitle">Geschiedenis</p>
        <div class="list__accordion">
          <div class="collapsible-accordion" v-for="item in user.history">
            <div class="collapsible-item">
              <input type="checkbox" :id="`item-${item.id}`" />
              <label class="collapsible-item-label" :for="`item-${item.id}`"
                >{{ item.name }}&nbsp;{{ item.year }}</label
              >
              <div class="collapsible-item-content">
                <p v-for="list in item.productlists">Lijst {{ list.listNumber }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const { data: userData, pending: userPending } = myLazyFetch(
  () => `/api/user/${useRoute().params.id}`,
  {
    key: "user",
    params: {
      includeList: true,
      includeListHistory: true,
    },
  }
);

const user = computed(() => {
  if (!userData) return {};
  if (!userData.value) return {};
  return userData.value.data;
});
</script>
