<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Lijstenbeheer</template>
    </LayoutPageHeading>
    <section class="section__list-management">
      <div class="list-management__filters">
        <div class="row align-items-center mb-3">
          <div class="col-3">
            <div class="filters__search">
              <input class="form-control" name="search" id="search" @input="keywordChange" placeholder="Zoeken"/>
            </div>
          </div>

          <div class="col-3">
            <div class="filters filters__confirmed">
              <p>Bevestigd</p>
              <select class="selection__confirmed" v-model="isUserConfirmed">
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
          <div class="col-3">
            <div class="filters filters__validated">
              <p>Gevalideerd</p>
              <select class="selection__confirmed" v-model="isEmployeeValidated">
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
          <div class="col-3">
            <div class="filters filters__paid">
              <p>Uitbetaald</p>
              <select class="selection__confirmed" v-model="isPaidToUser">
                <option value="any">Alles</option>
                <option value="yes">Ja</option>
                <option value="no">Nee</option>
              </select>
            </div>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col">
          <div class="list-management__table">
            <div class="table__headers">
              <div class="header__number">#</div>
              <div class="header__first-name">Voornaam</div>
              <div class="header__last-name">Achternaam</div>
              <div class="header__member-number">Lidnummer</div>
              <div class="header__confirmed">Bevestigd</div>
              <div class="header__validated">Gevalideerd</div>
              <div class="header__actions">Acties</div>
            </div>
            <div class="table__items">
              <div class="item" v-for="item in lists">
                <div class="item__number">{{ item.listNumber }}</div>
                <div class="item__first-name">{{ item.user && item.user.firstname }}</div>
                <div class="item__last-name">{{ item.user && item.user.lastname }}</div>
                <div class="item__member-number">{{ emptyCheck(item.memberNumber) }}</div>
                <div class="item__confirmed">{{ item.isUserConfirmed }}</div>
                <div class="item__validated">{{ item.isEmployeeValidated }}</div>
                <div class="item__actions">
                  <NuxtLink class="btn btn-primary btn-sm" :to="`/list-management/${item.id}`">
                    <i class="fas fa-eye"></i>
                  </NuxtLink>
                </div>
              </div>
            </div>
          </div>
<!--          <table class="table">-->
<!--            <thead>-->
<!--            <tr>-->
<!--              <th scope="col">#</th>-->
<!--              <th scope="col">Voornaam</th>-->
<!--              <th scope="col">Naam</th>-->
<!--              <th scope="col">Lidnummer</th>-->
<!--              <th scope="col">Bevestigd</th>-->
<!--              <th scope="col">Gevalideerd</th>-->
<!--              <th scope="col"></th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr v-for="i in 15" class="placeholder-glow" v-if="pending">-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--              <td><span class="placeholder w-100"></span></td>-->
<!--            </tr>-->
<!--            <tr v-for="item in lists" v-else>-->
<!--              <th scope="row">{{ item.listNumber }}</th>-->
<!--              <td>{{ item.user.firstname }}</td>-->
<!--              <td>{{ item.user.lastname }}</td>-->
<!--              <td>{{ emptyCheck(item.memberNumber) }}</td>-->
<!--              <td>{{ item.isUserConfirmed }}</td>-->
<!--              <td>{{ item.isEmployeeValidated }}</td>-->
<!--              <td>-->
<!--                <NuxtLink class="btn btn-primary btn-sm" :to="`/list-management/${item.id}`">-->
<!--                  <i class="fas fa-eye"></i>-->
<!--                </NuxtLink>-->
<!--              </td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--          </table>-->
          <Pagination
              :page="pagination.current_page"
              :from="pagination.from"
              :to="pagination.to"
              :total="pagination.total"
              :last-page="pagination.last_page"
          />
        </div>

      </div>
    </section>
  </div>
</template>
<script setup>
useHead({
  title: "Lijstenbeheer",
});
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

const search = ref("");
const isUserConfirmed = ref("any");
const isEmployeeValidated = ref("any");
const isPaidToUser = ref("any");
const query = computed(() => {
  let query = "&isUserConfirmed[]=0&isUserConfirmed[]=1";
  query += "&isEmployeeValidated[]=0&isEmployeeValidated[]=1";
  query += "&isPaidToUser[]=0&isPaidToUser[]=1";
  if (isUserConfirmed.value == "yes") query = query.replace("isUserConfirmed[]=0", "");
  if (isUserConfirmed.value == "no") query = query.replace("isUserConfirmed[]=1", "");
  if (isEmployeeValidated.value == "yes")
    query = query.replace("isEmployeeValidated[]=0", "");
  if (isEmployeeValidated.value == "no")
    query = query.replace("isEmployeeValidated[]=1", "");
  if (isPaidToUser.value == "yes") query = query.replace("isPaidToUser[]=0", "");
  if (isPaidToUser.value == "no") query = query.replace("isPaidToUser[]=1", "");

  return query;
});

const page = computed(() => {
  return parseInt(useRoute().query.page) || 1;
});

const {pending, data} = myAsyncData(
    () =>
        `/api/productlist?page=${page.value}&includeUser=true&search=${search.value}${query.value}`,
    {},
    {
      watch: [page, search, query],
    }
);

const pagination = computed(() => {
  if (!data) return {};
  if (!data.value) return {};
  return data.value.meta;
});

const lists = computed(() => {
  if (!data) return [];
  if (!data.value) return [];
  return data.value.data;
});

const debounce = ref(null);

const keywordChange = (e) => {
  clearTimeout(debounce.value);
  debounce.value = setTimeout(() => {
    search.value = e.target.value;
  }, 500);
};
</script>
