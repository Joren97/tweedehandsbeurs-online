<template>
  <section class="section__list-detail">
    <table class="list-detail__table">
      <thead>
      <tr>
        <th>#</th>
        <th>Beschrijving</th>
        <th>Vraagprijs</th>
        <th>Verkoopprijs</th>
        <th>Verkocht</th>
        <th>Acties</th>
      </tr>
      </thead>
      <tbody class="placeholder-glow" v-if="loading">
      <tr v-for="i in 5">
        <td>
          <div class="placeholder-glow">
            <div class="placeholder w-100"></div>
          </div>
        </td>
        <td>
          <div class="placeholder-glow">
            <div class="placeholder w-100"></div>
          </div>
        </td>
        <td>
          <div class="placeholder-glow">
            <div class="placeholder w-100"></div>
          </div>
        </td>
        <td>
          <div class="placeholder-glow">
            <div class="placeholder w-100"></div>
          </div>
        </td>
        <td>
          <div class="placeholder-glow">
            <div class="placeholder w-100"></div>
          </div>
        </td>
        <td></td>
      </tr>
      </tbody>
      <tbody v-else>
      <tr v-for="item in products">
        <td class="table__column">{{ item.productNumber }}</td>
        <td class="table__column column__description">{{ item.description }}</td>
        <td class="table__column">{{ toEuro(item.price.askingPrice) }}</td>
        <td class="table__column">{{ toEuro(item.price.sellingPrice) }}</td>
        <td class="table__column">
          <span v-if="item.isSold"> <i class="fa-solid fa-check"/></span>
          <span v-else> <i class="fa-solid fa-xmark"/></span>
        </td>
        <td class="table__column column__actions">
          <button class=""><i class="fa-solid fa-trash"></i></button>
          <button class="" @click="showModal = true"><i class="fa-solid fa-pencil"></i></button>
          <button class="" @click="sell(item, false)" v-if="item.isSold">
            <i class="fa-solid fa-close"></i>
          </button>
          <button class="" @click="sell(item, true)" v-if="!item.isSold">
            <i class="fa-solid fa-euro-sign"></i>
          </button>
        </td>
      </tr>
      <tr>
        <td colspan="2">Totaal verkocht</td>
        <td>{{ toEuro(totalSold) }}</td>
        <td colspan="3"></td>
      </tr>
      </tbody>
    </table>
    <EditProductModal v-if="showModal"/>
  </section>
</template>
<script setup>
const props = defineProps({
  products: {
    type: [Array, null],
    required: false,
    default: null,
  },
  loading: {
    type: Boolean,
    required: false,
    default: false,
  },
  data: function () {
    return {
      showModal: false
    }
  }
});


const emit = defineEmits(["refresh"]);

const totalSold = computed(() => {
  if (props.products) {
    let total = 0.0;
    const soldProducts = props.products.filter((product) => product.isSold);
    soldProducts.forEach((product) => {
      total += product.price.askingPrice;
    });
    return total;
  }
  return 0;
});


const sell = async (product, sold) => {
  const newProduct = {...product, isSold: sold};
  const {
    data: {
      value: {status, message, data},
    },
  } = await myFetch(`/api/product/${product.id}`, {
    method: "PUT",
    body: newProduct,
  });

  if (status == "Success") {
    emit("refresh");
  } else {
    console.log("Error");
  }
};
</script>
