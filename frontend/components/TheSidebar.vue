<template>
  <aside class="c-sidebar">
    <div class="sidebar__top">Tweedehandsbeurs Online</div>
    <div class="sidebar__navigation">
      <nav>
        <ul>
          <li>
            <NuxtLink to="/"> <i class="fas fa-fw fa-tachometer-alt"></i>Dashboard </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/lists"> <i class="far fa-list-alt"></i>Mijn Lijsten </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/profile"> <i class="far fa-user-circle"></i> Profiel </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/prices"><i class="far fa-money-bill-alt"></i> Prijslijst </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/list-management"><i class="fab fa-sistrix"></i> Lijstoverzicht </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/sell"><i class="fas fa-gavel"></i> Verkopen </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/user-management"><i class="far fa-address-book"></i> Gebruikers </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/editions"><i class="fas fa-cogs"></i> Edities </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/price-management"><i class="fas fa-money-check-alt"></i> Prijsoverzicht </NuxtLink>
          </li>
          <li>
            <NuxtLink to="/admin-dashboard"><i class="fas fa-chart-line"></i> Beursoverzicht </NuxtLink>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>
<script setup lang="ts"></script>
