<template>
  <section class="dashboard__lists">
    <div class="row">
      <div class="col">
        <div class="lists__title">Mijn lijsten</div>
      </div>
      <div class="col">
        <div class="lists__buttons">
          <button type="button" class="btn btn-primary" @click="addListVisible = true">
            <i class="fa-solid fa-circle-plus"></i>Lijst toevoegen
          </button>
        </div>
      </div>
    </div>
    <div class="lists__grid">
      <div class="grid__item placeholder-glow" v-if="pending" v-for="i in 4">
        <div class="item__title"><span class="placeholder col-4"></span></div>
        <div class="item__lists">
          <div class="lists__emtpy"><span class="placeholder col-12"></span></div>
        </div>
      </div>
      <div class="grid__item" v-for="item in editionsWithLists" :key="item.id" v-else>
        <div class="item__title">{{ item.year }} - {{ item.name }}</div>
        <div class="item__lists" v-if="item.lists.length > 0">
          <div
            class="lists__item"
            v-for="list in item.lists"
            :key="list.id"
            @click="openList(list.id)"
          >
            <div class="item__content">
              Lijst {{ list.listNumber }}&nbsp;&dash;&nbsp;({{
                list.memberNumber ?? "Geen lidnummer"
              }})
            </div>
            <div class="item__actions">
              <button type="button"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
        </div>
        <div class="item__lists" v-else>
          <div class="lists__emtpy">Geen lijsten</div>
        </div>
      </div>
    </div>

    <Modal :visible="addListVisible" @close="closeNewListModal()">
      <template v-slot:title>Nieuw lijst toevoegen</template>
      <template v-slot:content>
        <ListForm
          ref="newListForm"
          @submit="onNewListSubmit"
          :active-edition="activeEdition"
        />
      </template>
      <template v-slot:footer>
        <button type="button" class="btn btn-secondary mx-2" @click="closeNewListModal()">
          Sluiten
        </button>
        <LoadingButton @click="submitNewList" :loading="loading" type="primary"
          >Product toevoegen</LoadingButton
        >
      </template>
    </Modal>
  </section>
</template>

<script setup>
useHead({
  title: "Mijn lijsten",
});
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});

const { pending: editionsPending, data: editionsData } = myLazyFetch(
  () => `/api/edition`,
  { key: "edition", initialCache: false }
);
const {
  pending: listsPending,
  data: listsData,
  refresh: listsRefresh,
} = myLazyFetch(() => `/api/productlist/me`, { key: "productlist", initialCache: false });

const pending = computed(() => {
  return editionsPending.value || listsPending.value;
});

const editionsWithLists = computed(() => {
  if (!editionsData) return [];
  if (!editionsData.value) return [];
  if (!listsData) return [];
  if (!listsData.value) return [];

  return editionsData.value.data.map((edition) => {
    return {
      ...edition,
      lists: listsData.value.data.filter((list) => list.editionId === edition.id),
    };
  });
});

const activeEdition = computed(() => {
  if (!editionsData) return null;
  if (!editionsData.value) return null;
  if (!editionsData.value.data) return null;

  return editionsData.value.data.find((edition) => edition.isActive);
});

const openList = (id) => {
  useRouter().push(`/lists/${id}`);
};

/* Add new list */
const loading = ref(false);
const newListForm = ref();
const addListVisible = ref(false);

const submitNewList = async () => {
  await newListForm.value.submit();
};

const closeNewListModal = () => {
  newListForm.value.handleReset();
  addListVisible.value = false;
};

const onNewListSubmit = async (values) => {
  loading.value = true;
  console.log(values);
  const { data: resData, error: errorData } = await useApi(`/api/productlist/me`, {
    method: "POST",
    body: values,
  });

  if (errorData.value) {
    const {
      data: { errors },
    } = errorData.value;
    newListForm.value.setErrors(errors);
  } else {
    addListVisible.value = false;
    newListForm.value.handleReset();
    listsRefresh();
  }
  loading.value = false;
};
</script>
