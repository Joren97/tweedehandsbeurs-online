<template>
  <section class="section__user-management-detail">
    <div class="title">Gebruikersinfo</div>
    <div class="row mb-3">
      <div class="col-4">
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
    </div>
    <div class="row">
      <div class="subtitle">Huidige editie</div>
      <div class="col">
        <div class="datatable" :class="{ 'is-loading': currentPending || loading }">
          <div class="datatable__loading">
            <div class="loading__background"></div>
            <div class="sp sp-wave"></div>
          </div>
          <table class="datatable__table">
            <thead>
              <th>Lijstnummer</th>
              <th>Lidnummer</th>
              <th>Bevestigd</th>
              <th>Gevalideerd</th>
              <th>Uitbetaald</th>
              <th>Opbrengst</th>
              <th></th>
            </thead>
            <tbody>
              <tr v-for="item in current" :key="item.id">
                <td>{{ emptyCheck(item.listNumber) }}</td>
                <td>{{ emptyCheck(item.memberNumber) }}</td>
                <td><YesNoIcon :value="item.isUserConfirmed" /></td>
                <td><YesNoIcon :value="item.isEmployeeValidated" /></td>
                <td><YesNoIcon :value="item.isPaidToUser" /></td>
                <td>
                  {{ toEuro(item.userProfit) }}&nbsp;<span v-if="!item.memberNumber"
                    >(&nbsp;&dash;&nbsp;&euro;5 = {{ toEuro(item.userProfit - 5) }})</span
                  >
                </td>
                <td class="datatable__actions">
                  <span class="divider"></span>
                  <span class="action">
                    <NuxtLink :to="`/list-management/${item.id}`">
                      <i class="fa-regular fa-eye fa-lg"></i>
                    </NuxtLink>
                  </span>
                  <span
                    class="action"
                    :class="{ disabled: item.isPaidToUser }"
                    @click="confirmPayList(item)"
                    ><i class="fa-solid fa-coins"></i>
                  </span>
                </td>
              </tr>
              <tr>
                <td class="text-bold">Totaal</td>
                <td colspan="4"></td>
                <td>
                  <span v-if="totalWithholding != 0">
                    {{ toEuro(totalSold) }} - {{ toEuro(totalWithholding) }} =
                    <span class="text-bold">{{
                      toEuro(totalSold - totalWithholding)
                    }}</span></span
                  >
                  <span v-else class="text-bold">{{ toEuro(totalSold) }}</span>
                </td>
                <td class="datatable__actions">
                  <span class="divider"></span>
                  <span
                    class="action"
                    :class="{ disabled: allPaid }"
                    @click="confirmPayAll()"
                    ><i class="fa-solid fa-coins"></i>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <TheNotification class="mt-3" />
    <!-- History blocks -->
    <div class="row mt-3">
      <div class="col">
        <p class="subtitle">Geschiedenis</p>
      </div>
    </div>
    <!-- Placeholder history -->
    <div class="row placeholder-glow" v-if="historyPending">
      <div class="col-4">
        <div class="placeholder w-100"></div>
      </div>
      <div class="col-4">
        <div class="placeholder w-100"></div>
      </div>
      <div class="col-4">
        <div class="placeholder w-100"></div>
      </div>
    </div>

    <!-- History is empty -->
    <div class="row" v-else-if="editionHistory.length == 0">
      <div class="col">
        <p>Geen geschiedenis gevonden</p>
      </div>
    </div>

    <!-- History data -->
    <div class="row list__accordion" v-else>
      <div class="accordion__item col-4" v-for="item in editionHistory">
        <div class="collapsible-accordion">
          <div class="collapsible-item">
            <input type="checkbox" :id="`item-${item.id}`" />
            <label class="collapsible-item-label" :for="`item-${item.id}`"
              >{{ item.name }}&nbsp;{{ item.year }}</label
            >
            <div class="collapsible-item-content">
              <p v-for="list in item.productlists" class="list">
                <span>Lijst {{ list.listNumber }}</span>
                <span
                  ><NuxtLink :to="`/list-management/${list.id}`">
                    <i class="fa-regular fa-eye fa-lg"></i>
                  </NuxtLink>
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirm modal -->
    <Modal :visible="confirmPayVisible" @close="closeConfirmPay()">
      <template v-slot:title>Lijst uitbetalen</template>
      <template v-slot:content>
        <p>Ben je zeker dat je volgende lijsten wil uitbetalen:</p>
        <ul>
          <li v-for="item in selectedLists">Lijst {{ item.listNumber }}</li>
        </ul>
      </template>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary mx-2" @click="closeConfirmPay()">
          Annuleren
        </button>

        <LoadingButton type="primary" @click="paySelectedLists" :loading="loading"
          >Uitbetalen</LoadingButton
        >
      </template>
    </Modal>
  </section>
</template>
<script setup>
import { useNotificationStore } from "~~/store/notification";
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const loading = ref(false);
const notificationStore = useNotificationStore();

const { data: userData, pending: userPending } = myLazyFetch(
  () => `/api/user/${useRoute().params.id}`,
  {
    key: "user",
    initialCache: false,
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
    initialCache: false,
  }
);

const {
  data: currentData,
  pending: currentPending,
  refresh: currentRefresh,
} = myLazyFetch(() => `/api/productlist`, {
  key: "current",
  params: {
    "userId[eq]": useRoute().params.id,
    current: true,
  },
  initialCache: false,
});

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
        edition.productlists.push(item);
      }
    });
  });

  return editions;
});

const totalSold = computed(() => {
  if (!currentData) return 0;
  if (!currentData.value) return 0;
  return currentData.value.data.reduce((acc, item) => {
    return acc + item.userProfit;
  }, 0);
});

const totalWithholding = computed(() => {
  if (!currentData) return 0;
  if (!currentData.value) return 0;
  return currentData.value.data.reduce((acc, item) => {
    if (!item.memberNumber) return acc + 5;
    return acc;
  }, 0);
});

const allPaid = computed(() => {
  if (!currentData) return false;
  if (!currentData.value) return false;
  return currentData.value.data.every((item) => item.isPaidToUser);
});

/* Pay list */
const confirmPayVisible = ref(false);
const selectedLists = ref([]);

const confirmPayList = (list) => {
  confirmPayVisible.value = true;
  selectedLists.value = [list];
};

const confirmPayAll = () => {
  confirmPayVisible.value = true;
  selectedLists.value = current.value;
};

const closeConfirmPay = () => {
  confirmPayVisible.value = false;
  selectedLists.value = [];
};

const paySelectedLists = async () => {
  loading.value = true;

  const listIds = selectedLists.value.map((item) => item.id);

  loading.value = true;
  const { pending, error } = await useApi(`/api/productlist/pay`, {
    method: "POST",
    key: "payLists",
    initialCache: false,
    body: {
      productlistIds: listIds,
    },
  });

  if (error.value != null) {
    notificationStore.addNotification("Error", error.value.data.message);
  } else {
    notificationStore.addNotification("Success", "Lijsten uitbetaald");
    selectedLists.value = [];
    currentRefresh();
  }

  confirmPayVisible.value = false;
  loading.value = false;
};
/* End pay list */
</script>
