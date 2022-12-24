<template>
  <aside class="c-sidebar">
    <div class="sidebar__top">Tweedehandsbeurs <span>Online</span></div>
    <div class="sidebar__navigation">
      <nav>
        <ul>
          <li>
            <NuxtLink to="/" :class="active('/')">
              <i class="fas fa-fw fa-tachometer-alt"></i>Dashboard
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
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>
<script setup lang="ts">
const active = (path: string) => {
  const route = useRoute();
  if (path === "/") return route.path === "/" ? "active" : "";
  return route.path.includes(path) ? "active" : "";
};
</script>
