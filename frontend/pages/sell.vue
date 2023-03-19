<template>
  <section class="section__sell">
    <LayoutPageHeading>
      <template v-slot:title>Verkopen</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col-6">
        <form>
          <div class="row mb-3">
            <label for="listNumber" class="form-label">Lijstnummer</label>
            <VField name="listNumber" as="div" v-slot="{ field, meta }">
              <input
                v-bind="field"
                type="text"
                name="listNumber"
                @keyup.enter="next"
                ref="listNumberField"
                class="form-control"
                :class="{
                  'is-valid': meta.valid && meta.touched,
                  'is-invalid': !meta.valid && meta.touched,
                }"
              />
              <div class="invalid-feedback">{{ errors.listNumber }}</div>
            </VField>
          </div>

          <div class="row mb-3">
            <label for="productNumber" class="form-label">Productnummer</label>
            <VField name="productNumber" as="div" v-slot="{ field, meta }">
              <input
                v-bind="field"
                type="text"
                name="productNumber"
                @keyup.enter="next"
                ref="productNumberField"
                class="form-control"
                :class="{
                  'is-valid': meta.valid && meta.touched,
                  'is-invalid': !meta.valid && meta.touched,
                }"
              />
              <div class="invalid-feedback">{{ errors.productNumber }}</div>
            </VField>
          </div>
          <button
            type="button"
            class="btn btn-loading btn-primary"
            :class="{ loading: searchLoading }"
            ref="submitButton"
            @click="searchProduct"
          >
            <span class="btn-text">Zoeken</span>
            <span
              class="spinner-border spinner-border-sm"
              role="status"
              aria-hidden="true"
            ></span>
          </button>
        </form>
      </div>
      <div class="col-6">
        <div class="product__information">
          <div class="information__title">
            <span>Productinfo</span>
          </div>
          <div class="information__content placeholder-glow">
            <div class="information__item mb-2">
              <span class="item__title">Beschrijving</span>
              <span v-if="!product">&dash;</span>
              <span v-else>{{ product && product.description }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Vraagprijs</span>
              <span v-if="!product">&dash;</span>
              <span v-else>{{ product && toEuro(product.price.askingPrice) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Verkoopprijs</span>
              <span v-if="!product">&dash;</span>
              <span v-else>{{ product && toEuro(product.price.sellingPrice) }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Productnummer</span>
              <span v-if="!product">&dash;</span>
              <span v-else class="text-bold">{{ product && product.productNumber }}</span>
            </div>
            <div class="information__item mb-2">
              <span class="item__title">Lijstnummer</span>
              <span v-if="!product">&dash;</span>
              <span v-else>{{
                product && product.productlist && product.productlist.listNumber
              }}</span>
            </div>
          </div>
        </div>
        <div class="mt-3">
          <button
            type="button"
            :disabled="!product || product.isSold"
            class="btn btn-loading btn-primary"
            :class="{ loading: sellLoading }"
            ref="sellButton"
            @click="sellProduct(product)"
          >
            <span class="btn-text" v-if="!product">Verkoop</span>
            <span class="btn-text" v-else-if="product && product.isSold"
              >Product is verkocht</span
            >
            <span v-else class="btn-text">Verkoop</span>
            <span
              class="spinner-border spinner-border-sm"
              role="status"
              aria-hidden="true"
            ></span>
          </button>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <TheNotification />
      </div>
    </div>
  </section>
</template>
<script setup>
import { object, string } from "yup";
import { useNotificationStore } from "~~/store/notification";
import { useForm } from "vee-validate";
const notificationStore = useNotificationStore();

definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "employee",
  },
});

useHead({
  title: "Verkopen",
});

const validationSchema = object({
  productNumber: string().required().matches(/^\d*$/, {
    message: "Enkel cijfers zijn toegelaten",
  }),
  listNumber: string().required().matches(/^\d*$/, {
    message: "Enkel cijfers zijn toegelaten",
  }),
});

const { handleSubmit, errors } = useForm({
  validationSchema,
});

const productNumberField = ref();
const listNumberField = ref();
const submitButton = ref();

const next = (e) => {
  const fieldName = e.srcElement.name;

  if (errors.value[fieldName]) return;

  if (fieldName === "productNumber") {
    listNumberField.value.focus();
  } else if (fieldName === "listNumber") {
    searchProduct();
  }
};

const product = ref(null);
const searchLoading = ref(false);
const sellLoading = ref(false);
const sellButton = ref();

const searchProduct = handleSubmit(async (values, actions) => {
  searchLoading.value = true;
  const { data: resData } = await useApi(`/api/product`, {
    method: "GET",
    params: {
      "listNumber[eq]": values.listNumber,
      "productNumber[eq]": values.productNumber,
      includeProductlist: true,
    },
  });
  const { data, message, status } = resData.value;
  searchLoading.value = false;

  if (status === "Error") {
    notificationStore.addNotification(status, message);
  } else if (data.length === 0) {
    notificationStore.addNotification("Error", "Product niet gevonden");
  } else {
    product.value = data[0];
    await nextTick();
    sellButton.value.focus();
  }

  actions.resetForm();

  if (status === "Error") productNumberField.value.focus();
  else if (data.length === 0) productNumberField.value.focus();
  else if (data[0].isSold) productNumberField.value.focus();
});

const sellProduct = async (p) => {
  sellLoading.value = true;
  const { data: resData } = await useApi(`/api/product/${p.id}`, {
    method: "PUT",
    body: {
      ...p,
      isSold: true,
    },
  });
  const { data, message, status } = resData.value;
  sellLoading.value = false;

  if (status === "Success") {
    notificationStore.addNotification(status, message);
    productNumberField.value.focus();
    product.value = data;
  }
};
</script>
