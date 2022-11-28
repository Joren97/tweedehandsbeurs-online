<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title
        ><p class="placeholder-glow">
          <span v-if="pending" class="placeholder">Lijst xxx</span>
          <span v-else>Lijst {{ list.listNumber }}</span>
        </p></template
      >
    </LayoutPageHeading>
    <div class="row">
      <div class="col-8">
        {{ list.products }}
      </div>
      <div class="col-4">
        <table class="table">
          <thead>
            <tr>
              <th colspan="2">Lijstinfo</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Lijstnummer</td>
              <td class="placeholder-glow">
                <span class="w-100 placeholder" v-if="pending"></span>
                <span v-else>{{ list.listNumber }}</span>
              </td>
            </tr>
            <tr>
              <td>Lidnummer</td>
              <td class="placeholder-glow">
                <span class="w-100 placeholder" v-if="pending"></span>
                <span v-else>{{ emptyCheck(list.memberNumber) }}</span>
              </td>
            </tr>
            <tr>
              <td>Bevestigd</td>
              <td>
                <span v-if="list.isEmployeeValidated">
                  <i class="fa-solid fa-check" /></span
                ><span v-else> <i class="fa-solid fa-xmark" /></span>
              </td>
            </tr>
            <tr>
              <td>Gevalideerd</td>
              <td>
                <span v-if="list.isValidatedByEmployee">
                  <i class="fa-solid fa-check" /></span
                ><span v-else> <i class="fa-solid fa-xmark" /></span>
              </td>
            </tr>
            <tr>
              <td>Uitbetaald</td>
              <td>TOOD</td>
            </tr>
            <tr>
              <td>Te betalen:</td>
              <td>TODO</td>
            </tr>
          </tbody>
        </table>
        <table class="table">
          <tbody>
            <tr>
              <th colspan="2">Gebruikersinfo</th>
            </tr>
            <tr>
              <td>Naam</td>
              <td>{{ user.firstname }} {{ user.lastname }}</td>
            </tr>
            <tr>
              <td>Email</td>
              <td>{{ user.email }}</td>
            </tr>
            <tr>
              <td>Lidnummer</td>
              <td>{{ emptyCheck(user.memberNumber) }}</td>
            </tr>
            <tr>
              <td>Telefoon</td>
              <td>{{ emptyCheck(user.phoneNumber) }}</td>
            </tr>
            <tr>
              <td>Adres</td>
              <td>{{ emptyCheck(fullAddress) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    {{ pending }}
    {{ list }}
  </div>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const { pending, data } = myFetch(
  `/api/productlist/${useRoute().params.id}?includeUser=true&includeProducts=true`
);

const list = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.data;
});

const user = computed(() => {
  if (!list.value) return {};
  if (!list.value.user) return {};
  return list.value.user;
});

const fullAddress = computed(() => {
  if (!user.value) return "";
  if (user.value.address && user.value.postalCode && user.value.city) {
    return `${user.value.address}, ${user.value.postalCode} ${user.value.city}`;
  }
  return "";
});
</script>
