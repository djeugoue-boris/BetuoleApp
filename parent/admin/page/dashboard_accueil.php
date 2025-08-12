<main class="content" role="main" tabindex="-1" aria-live="polite">
  <h1>Bienvenue sur le Tableau de bord</h1>
  <p class="lead">Aperçu global des activités de Betuole Académie</p>

  <section class="stats-grid" aria-label="Statistiques principales">
    <article class="stat-card stat-admin" tabindex="0" aria-label="Nombre d'administrateurs">
      <h4><i class="fa-solid fa-user-shield"></i> Administrateurs</h4>
      <p class="value"><?= $stats['admins'] ?></p>
    </article>
    <article class="stat-card stat-apprenant" tabindex="0" aria-label="Nombre d'apprenants">
      <h4><i class="fa-solid fa-user-graduate"></i> Apprenants</h4>
      <p class="value"><?= $stats['apprenants'] ?></p>
    </article>
    <article class="stat-card stat-formation" tabindex="0" aria-label="Nombre de formations">
      <h4><i class="fa-solid fa-book-open"></i> Formations</h4>
      <p class="value"><?= $stats['formations'] ?></p>
    </article>
    <article class="stat-card stat-paiement" tabindex="0" aria-label="Nombre de paiements">
      <h4><i class="fa-solid fa-money-bill-wave"></i> Paiements</h4>
      <p class="value"><?= $stats['paiements'] ?></p>
    </article>
    <article class="stat-card stat-actualite" tabindex="0" aria-label="Nombre d'actualités">
      <h4><i class="fa-solid fa-newspaper"></i> Actualités</h4>
      <p class="value"><?= $stats['actualites'] ?></p>
    </article>
    <article class="stat-card stat-annee-active" tabindex="0" aria-label="Année active">
      <h4><i class="fa-solid fa-calendar"></i> Année active</h4>
      <p class="value"><?= htmlspecialchars($stats['annee_active']) ?></p>
    </article>
    <article class="stat-card stat-archive" tabindex="0" aria-label="Nombre d'archives">
      <h4><i class="fa-solid fa-archive"></i> Archives</h4>
      <p class="value"><?= $stats['archives'] ?></p>
    </article>
    <article class="stat-card stat-annees-archivees" tabindex="0" aria-label="Nombre d'années archivées">
      <h4><i class="fa-solid fa-box-archive"></i> Années archivées</h4>
      <p class="value"><?= $stats['annees_archivees'] ?></p>
    </article>
  </section>

  <section id="chartsTabs" class="mt-5" aria-label="Graphiques d'évolution">
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="global-tab" data-bs-toggle="tab" data-bs-target="#global" type="button" role="tab" aria-controls="global" aria-selected="true">Global</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">Utilisateurs</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="formations-tab" data-bs-toggle="tab" data-bs-target="#formations" type="button" role="tab" aria-controls="formations" aria-selected="false">Formations</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="paiements-tab" data-bs-toggle="tab" data-bs-target="#paiements" type="button" role="tab" aria-controls="paiements" aria-selected="false">Paiements</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="actualites-tab" data-bs-toggle="tab" data-bs-target="#actualites" type="button" role="tab" aria-controls="actualites" aria-selected="false">Actualités</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="annees-tab" data-bs-toggle="tab" data-bs-target="#annees" type="button" role="tab" aria-controls="annees" aria-selected="false">Années d'activité</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="archives-tab" data-bs-toggle="tab" data-bs-target="#archives" type="button" role="tab" aria-controls="archives" aria-selected="false">Archives</button>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="global" role="tabpanel" aria-labelledby="global-tab">
        <canvas id="globalChart" class="mt-4" role="img" aria-label="Graphique en barres des statistiques globales"></canvas>
      </div>
      <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
        <canvas id="usersChart" class="mt-4" role="img" aria-label="Graphique en ligne des utilisateurs"></canvas>
      </div>
      <div class="tab-pane fade" id="formations" role="tabpanel" aria-labelledby="formations-tab">
        <canvas id="formationsChart" class="mt-4" role="img" aria-label="Graphique en ligne des formations"></canvas>
      </div>
      <div class="tab-pane fade" id="paiements" role="tabpanel" aria-labelledby="paiements-tab">
        <canvas id="paiementsChart" class="mt-4" role="img" aria-label="Graphique en ligne des paiements"></canvas>
      </div>
      <div class="tab-pane fade" id="actualites" role="tabpanel" aria-labelledby="actualites-tab">
        <canvas id="actualitesChart" class="mt-4" role="img" aria-label="Graphique en ligne des actualités"></canvas>
      </div>
      <div class="tab-pane fade" id="annees" role="tabpanel" aria-labelledby="annees-tab">
        <canvas id="anneesChart" class="mt-4" role="img" aria-label="Graphique en ligne des années d'activité"></canvas>
      </div>
      <div class="tab-pane fade" id="archives" role="tabpanel" aria-labelledby="archives-tab">
        <canvas id="archivesChart" class="mt-4" role="img" aria-label="Graphique en ligne des archives"></canvas>
      </div>
    </div>
  </section>
</main>