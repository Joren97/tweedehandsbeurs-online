<template>
  <div>
    <LayoutPageHeading>
      <template v-slot:title>Verkopen</template>
    </LayoutPageHeading>
    <div class="row">
      <div class="col-4">
        <form>
          {{ meta }}
          {{ values }}
          {{ errors }}
          <label for="productNumber">Productnummer</label>
          <VField name="productNumber" @keyup.enter="next" ref="productNumber" />
          <VErrorMessage name="productNumber" as="div" class="invalid-feedback" />
          <label for="listNumber">Lijstnummer</label>
          <VField name="listNumber" @keyup.enter="next" ref="listNumber" />
          <VErrorMessage name="listNumber" as="div" class="invalid-feedback" />
          <LoadingButton @click="searchProduct" :loading="false" type="primary"
            >Opslaan</LoadingButton
          >
        </form>
      </div>
      <div class="col-8">
        <div v-if="product == null">
          <p>Zoek een product door de lijstnummer en productnummer in te geven.</p>
        </div>
        <div v-else>
          <table class="table">
            <tr>
              <td>Beschrijving:</td>
              <td>{{ product.description }}</td>
            </tr>
            <tr>
              <td>Vraagprijs:</td>
              <td>
                {{ product.price && product.price.askingPrice | toEuro }}
              </td>
            </tr>
            <tr>
              <td>Verkoopprijs:</td>
              <td>
                {{ product.price && product.price.sellingPrice | toEuro }}
              </td>
            </tr>
          </table>
          <button
            id="sell"
            class="btn btn-primary"
            :disabled="product.isSold"
            @click="sellProduct(product)"
          >
            {{ product.isSold ? "Reeds verkocht" : "Verkoop" }}
          </button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <TheNotification />
      </div>
    </div>
  </div>
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

const { handleSubmit, handleReset, meta, values, errors } = useForm({
  validationSchema,
});

const product = ref(null);

const searchProduct = handleSubmit(async (values, actions) => {
  console.log(values);
  // const x = await useCustomLazyFetch(
  //   `/api/product?listNumber[eq]=${values.listNumber}&productNumber[eq]=${values.productNumber}`
  // );
  // console.log(x);
  // const { status, message, data } = await useApi(
  //   `/api/product?listNumber[eq]=${values.listNumber}&productNumber[eq]=${values.productNumber}`
  // );
  // if (status === "Error") {
  //   notificationStore.message = message;
  //   notificationStore.status = status;
  // } else if (data.length === 0) {
  //   notificationStore.message = "Product niet gevonden";
  //   notificationStore.status = "Error";
  // } else {
  //   product.value = data[0];
  //   await nextTick();
  //   document.getElementById("sell").focus();
  // }
  // actions.resetForm();
  // if (status === "Error") document.getElementById("productNumber").focus();
  // if (data.length === 0) document.getElementById("productNumber").focus();
  // if (data[0].isSold) document.getElementById("productNumber").focus();
});

const sellProduct = async (p) => {
  const { status, message, data } = await useApi(`/api/product/${p.id}`, {
    method: "PUT",
    body: {
      ...p,
      isSold: true,
    },
  });

  if (status === "Success") {
    notificationStore.message = message;
    notificationStore.status = status;
    document.getElementById("productNumber").focus();
    product.value = data;
  }
};

const next = (e) => {
  const fieldName = e.srcElement.name;
  console.log(fieldName);
  // let nextElement = null;
  // e.preventDefault();
  // const {
  //   target: { id },
  // } = e;
  // if (id === "productNumber") {
  //   nextElement = document.getElementById("listNumber");
  // }

  // nextElement?.focus();
};
</script>
