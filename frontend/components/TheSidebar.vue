<template>
  <aside class="c-sidebar">
    <div class="sidebar__top">Tweedehandsbeurs <span>Online</span></div>
    <div class="sidebar__navigation">
      <nav>
        <ul>
          <template v-for="item in navItems">
            <li v-if="item.link">
              <NuxtLink :to="item.link" :class="active(item.link)">
                <i :class="item.icon"></i>{{ item.text }}
              </NuxtLink>
            </li>
            <li v-else-if="item.title" class="navigation__title">
              {{ item.title }}
            </li>
          </template>

          <!-- <li>
            <NuxtLink to="/" :class="active('/')">
              <i class="fa-solid fa-circle-info"></i>Homepagina
            </NuxtLink>
          </li>
          <li class="navigation__title">Gebruiker</li>
          <li>
            <NuxtLink to="/lists" :class="active('/lists')">
              <i class="far fa-list-alt"></i>Mijn Lijsten
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/profile" :class="active('/profile')">
              <i class="far fa-user-circle"></i> Profiel
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/prices" :class="active('/prices')"
              ><i class="far fa-money-bill-alt"></i> Prijslijst
            </NuxtLink>
          </li>
          <li class="navigation__title">Medewerker</li>
          <li>
            <NuxtLink to="/list-management" :class="active('/list-management')"
              ><i class="fab fa-sistrix"></i> Lijstoverzicht
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/sell" :class="active('/sell')"
              ><i class="fas fa-gavel"></i> Verkopen
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/user-management" :class="active('/user-management')"
              ><i class="far fa-address-book"></i> Gebruikers
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/product-management" :class="active('/product-management')"
              ><i class="fa-solid fa-shirt"></i> Producten
            </NuxtLink>
          </li>
          <li class="navigation__title">Admin</li>
          <li>
            <NuxtLink to="/editions" :class="active('/editions')"
              ><i class="fas fa-cogs"></i> Edities
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/price-management" :class="active('/price-management')"
              ><i class="fas fa-money-check-alt"></i> Prijsoverzicht
            </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/admin-dashboard" :class="active('/admin-dashboard')"
              ><i class="fas fa-chart-line"></i> Beursoverzicht
            </NuxtLink>
          </li> -->
        </ul>
      </nav>
    </div>
  </aside>
</template>
<script setup lang="ts">
import { useAuthStore } from "~~/store/auth";
const authStore = useAuthStore();

const active = (path: string) => {
  const route = useRoute();
  if (path === "/") return route.path === "/" ? "router-link-active" : "";
  return route.path.includes(path) ? "router-link-active" : "";
};

const navItems = computed(() => {
  const userItems = [
    {
      link: "/",
      icon: "fa-solid fa-circle-info",
      text: "Homepagina",
    },
    {
      title: "Gebruiker",
    },
    {
      link: "/lists",
      icon: "far fa-list-alt",
      text: "Mijn Lijsten",
    },
    {
      link: "/profile",
      icon: "far fa-user-circle",
      text: "Profiel",
    },
    {
      link: "/prices",
      icon: "far fa-money-bill-alt",
      text: "Prijslijst",
    },
  ];

  const employeeItems = [
    {
      title: "Medewerker",
    },
    {
      link: "/list-management",
      icon: "fab fa-sistrix",
      text: "Lijstoverzicht",
    },
    {
      link: "/sell",
      icon: "fas fa-gavel",
      text: "Verkopen",
    },
    {
      link: "/user-management",
      icon: "far fa-address-book",
      text: "Gebruikers",
    },
    {
      link: "/product-management",
      icon: "fa-solid fa-shirt",
      text: "Producten",
    },
  ];

  const adminItems = [
    {
      title: "Admin",
    },
    {
      link: "/editions",
      icon: "fas fa-cogs",
      text: "Edities",
    },
    {
      link: "/price-management",
      icon: "fas fa-money-check-alt",
      text: "Prijsoverzicht",
    },
    {
      link: "/admin-dashboard",
      icon: "fas fa-chart-line",
      text: "Beursoverzicht",
    },
  ];

  const items = [...userItems];
  if (authStore.getRole === "employee") items.push(...employeeItems);
  if (authStore.getRole === "admin") items.push(...adminItems);
  return items;
});
</script>
