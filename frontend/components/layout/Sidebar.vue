<template>
  <!-- Sidebar -->
  <ul
    class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
    id="accordionSidebar"
  >
    <!-- Sidebar - Brand -->
    <a
      class="sidebar-brand d-flex align-items-center justify-content-center"
      href="index.html"
    >
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Tweedehandsbeurs Online</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <template v-for="sidebarItem in sidebar">
      <hr v-if="sidebarItem.type == 'divider'" class="sidebar-divider" />
      <div v-if="sidebarItem.type == 'heading'" class="sidebar-heading">
        {{ sidebarItem.text }}
      </div>
      <li
        class="nav-item"
        v-if="sidebarItem.type == 'link'"
        :class="{ active: route == sidebarItem.link }"
      >
        <NuxtLink :to="sidebarItem.link" class="nav-link">
          <i :class="sidebarItem.icon"></i>
          <span>{{ sidebarItem.text }}</span>
        </NuxtLink>
      </li>
    </template>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" />

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>
  <!-- End of Sidebar -->
</template>
<script setup lang="ts">
import { useAuthStore } from "~~/store/auth";

const route = computed(() => useRoute().path);

const sidebar = computed(() => {
  const user = useAuthStore().user;

  let sidebar = [
    {
      type: "link",
      link: "/",
      text: "Dashboard",
      icon: "fas fa-fw fa-tachometer-alt",
    },
    {
      type: "divider",
    },
    {
      type: "heading",
      text: "Algemeen",
    },
    {
      type: "link",
      link: "/lists",
      text: "Mijn lijsten",
      icon: "far fa-list-alt",
    },
    {
      type: "link",
      link: "/profile",
      text: "Profiel",
      icon: "far fa-user-circle",
    },
    {
      type: "link",
      link: "/prices",
      text: "Prijslijst",
      icon: "far fa-money-bill-alt",
    },
  ];

  if (user.role === "employee" || user.role === "admin") {
    sidebar.push(
      ...[
        {
          type: "divider",
        },
        {
          type: "heading",
          text: "Medewerker",
        },
        {
          type: "link",
          link: "/list-management",
          text: "Lijstoverzicht",
          icon: "fab fa-sistrix",
        },
        {
          type: "link",
          link: "/sell",
          text: "Verkopen",
          icon: "fas fa-gavel",
        },
        {
          type: "link",
          link: "/user-management",
          text: "Gebruikers",
          icon: "far fa-address-book",
        },
      ]
    );
  }

  if (user.role === "admin") {
    sidebar.push(
      ...[
        {
          type: "divider",
        },
        {
          type: "heading",
          text: "Admin",
        },
        {
          type: "link",
          link: "/editions",
          text: "Edities",
          icon: "fas fa-cogs",
        },
        {
          type: "link",
          link: "/price-management",
          text: "Prijsoverzicht",
          icon: "fas fa-money-check-alt",
        },
        {
          type: "link",
          link: "/admin-dashboard",
          text: "Beursoverzicht",
          icon: "fas fa-chart-line",
        },
      ]
    );
  }

  return sidebar;
});
</script>
