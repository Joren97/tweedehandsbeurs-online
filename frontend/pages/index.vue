<template>
  <section class="section__homepage">
    <div class="homepage__title">Homepagina</div>
    <p>
      Welkom bij Gezinsbond Geel. Fijn dat je meedoet met onze tweedehandsbeurs! Vooraleer
      je verder gaat in het systeem, gelieve de onderstaande stappen goed te lezen:
    </p>
    <div class="subtitle">Stap 1: Lijst(en) aanmaken</div>
    <p>
      Per aangekochte lijst maak je een lijst aan in deze tool via de knop &apos;mijn
      lijsten&apos; (zie links). Voor het aanmaken van een lijst heb je het lijstnummer
      nodig. Dit nummer vind je op de voorpagina rechts bovenaan van je gekochte lijst.
    </p>
    <p>
      Let op:
      <span class="text-bold"
        >Met &eacute;&eacute;n lidnummer kan je 2 lijsten aanmaken.</span
      >
      Heb je meer dan 2 lijsten, dan kan je de lijst wel aanmaken in het systeem zonder
      lidnummer, maar je zal bij de afrekening 5 euro betalen voor administratiekosten.
      Ook niet-leden betalen 5 euro per lijst bij de afrekening.
    </p>
    <div class="subtitle">Stap 2: Artikelen invullen</div>
    <p>
      Per lijst vul je online je 20 artikelen in. Nadat je de lijst hebt ingevuld, kan je
      de lijst bevestigen.
    </p>
    <p>
      Let op:
      <span class="text-bold"
        >Na het bevestigen van de lijst kan je niets meer wijzigen!</span
      >
      Na het bevestigen ontvang je een e-mailbevestiging met in de bijlage een pdf van je
      lijst. Gelieve dit mee te brengen op zaterdagochtend bij het binnenbrengen van je
      artikelen.
    </p>
    <div class="subtitle">Stap 3: Vul je etiketten in</div>
    <p>
      Pas nadat je je lijsten bevestigd hebt, dan vul je je etiketten in. Op je gekochte
      lijst staat uitgelegd hoe je dat moet doen. Onze medewerkers controleren de
      etiketten aan de artikelen bij het binnenbrengen op zaterdagochtend.
    </p>
    <p>
      Tijdens de beurs kan je via deze tool je verkopen opvolgen. Lijsten van vorige
      beurzen blijven bewaard in jouw persoonlijk account.
    </p>
    <p>
      Heb je vragen bij het invullen van je artikelen, neem dan contact op met Evy Bens
      via telefoon <a href="tel:014723910">014/72.39.10</a> of mail naar
      <a href="mailto:evy.bens@gmail.com">evy.bens@gmail.com</a>
    </p>
  </section>
</template>
<script setup>
definePageMeta({
  layout: "dashboard",
  middleware: ["auth"],
  meta: {
    authLevel: "user",
  },
});
</script>
