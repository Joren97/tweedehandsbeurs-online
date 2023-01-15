<template>
  <section class="section__user-management-detail">
    <div class="title">Gebruikersinfo</div>
    <div class="row">
      <div class="col">
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
              <span v-else>TODO</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <p class="subtitle">Huidige editie</p>
        <div
          class="list__information mb-3"
          v-for="item in user.productlists"
          :key="item.id"
        >
          <div class="information__title">
            <span v-if="userPending" class="placeholder col-4"></span>
            <span v-else>Lijstnummer {{ item.listNumber }}</span>
          </div>
          <div class="information__content placeholder-glow">
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
              <span class="item__title">Jouw opbrengst</span>
              <span v-if="userPending" class="placeholder col-4"></span>
              <span v-else>{{ toEuro(item.userProfit) }}</span>
            </div>
          </div>
        </div>
        <p>{{ user.productlists }}</p>
      </div>
      <div class="col">
        <p class="subtitle">Geschiedenis</p>
        <div class="list__accordion">
          <div class="collapsible-accordion" v-for="item in history">
            <div class="collapsible-item">
              <input type="checkbox" id="tab1" />
              <label class="collapsible-item-label" for="tab1"
                >{{ item.name }}&nbsp;{{ item.year }}</label
              >
              <div class="collapsible-item-content">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum,
                reiciendis!
              </div>
            </div>
          </div>
          <div class="collapsible-accordion">
            <div class="collapsible-item">
              <input type="checkbox" id="tab2" />
              <label class="collapsible-item-label" for="tab2">Item 2</label>
              <div class="collapsible-item-content">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum,
                reiciendis!
              </div>
            </div>
          </div>
          <div class="collapsible-accordion">
            <div class="collapsible-item">
              <input type="checkbox" id="tab3" />
              <label class="collapsible-item-label" for="tab3">Item 3</label>
              <div class="collapsible-item-content">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsum,
                reiciendis!
              </div>
            </div>
          </div>
        </div>

        <!-- <p>{{ user.listHistory }}</p> -->
        <p>{{ history }}</p>
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

const history = computed(() => {
  if (!userData) return {};
  if (!userData.value) return {};

  let editions = [];

  // Get all editions from listHistory
  userData.value.data.listHistory.forEach((list) => {
    if (!editions.includes(list.edition)) {
      editions.push({ ...list.edition, lists: [] });
    }
  });

  // Add all lists to their respective edition
  // editions.forEach((edition) => {
  //   userData.value.data.listHistory.forEach((list) => {
  //     if (list.editionId === edition.id) {
  //       edition.lists.push(list);
  //     }
  //   });
  // });

  return editions;
});
</script>
