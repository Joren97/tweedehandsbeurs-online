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
        <table class="datatable">
          <thead>
            <th>Lijstnummer</th>
            <th>Lidnummer</th>
            <th>Bevestigd</th>
            <th>Gevalideerd</th>
            <th>Uitbetaald</th>
            <th>Opbrengst</th>
          </thead>
          <tbody>
            <tr v-for="item in current" :key="item.id">
              <td>{{ emptyCheck(item.listNumber) }}</td>
              <td>{{ emptyCheck(item.memberNumber) }}</td>
              <td><YesNoIcon :value="item.isUserConfirmed" /></td>
              <td><YesNoIcon :value="item.isEmployeeValidated" /></td>
              <td><YesNoIcon :value="item.isPaidToUser" /></td>
              <td>{{ toEuro(item.userProfit) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <p class="subtitle">Geschiedenis</p>
        <div class="list__accordion">
          <div class="collapsible-accordion" v-for="item in editionHistory">
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
  }
);

const { data: historyData, pending: historyPending } = myLazyFetch(
  () => `/api/productlist`,
  {
    key: "history",
    params: {
      "userId[eq]": useRoute().params.id,
      history: true,
    },
  }
);

const { data: currentData, pending: currentPending } = myLazyFetch(
  () => `/api/productlist`,
  {
    key: "current",
    params: {
      "userId[eq]": useRoute().params.id,
      current: true,
    },
  }
);

const user = computed(() => {
  if (!userData) return {};
  if (!userData.value) return {};
  return userData.value.data;
});

const current = computed(() => {
  if (!currentData) return [];
  if (!currentData.value) return [];
  return currentData.value.data;
});

const editionHistory = computed(() => {
  if (!historyData) return [];
  if (!historyData.value) return [];
  const history = historyData.value.data;

  // Get all unique editions
  let editionIds = [];
  let editions = [];

  // Get all unique editions
  history.forEach((item) => {
    if (!editionIds.includes(item.editionId)) {
      editionIds.push(item.editionId);
      editions.push({
        id: item.editionId,
        name: item.edition.name,
        year: item.edition.year,
        productlists: [],
      });
    }
  });

  // Get all productlists for each edition
  editions.forEach((edition) => {
    history.forEach((item) => {
      if (item.editionId === edition.id) {
        edition.productlists.push({
          listNumber: item.listNumber,
        });
      }
    });
  });

  return editions;
});
</script>
