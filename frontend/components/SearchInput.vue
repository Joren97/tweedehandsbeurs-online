<template>
  <input
    class="form-control"
    name="search"
    id="search"
    @input="keywordChange"
    placeholder="Zoeken"
  />
</template>
<script setup>
const debounce = ref(null);

const keywordChange = (e) => {
  clearTimeout(debounce.value);
  debounce.value = setTimeout(() => {
    if (e.target.value == "") {
      useRouter().push({
        query: {},
      });
    } else {
      useRouter().push({
        query: {
          search: e.target.value,
        },
      });
    }
  }, 500);
};
</script>
