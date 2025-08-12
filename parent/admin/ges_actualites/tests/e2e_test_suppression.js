const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({ headless: false, slowMo: 50 });
  const page = await browser.newPage();

  // Remplacer par l'URL locale correcte pour accéder à index.php
  const url = 'http://localhost/parent/admin/ges_actualites/index.php';

  try {
    await page.goto(url, { waitUntil: 'networkidle0' });

    // Vérifier la présence du tableau des actualités
    const tableExists = await page.$('table.table');
    if (tableExists) {
      console.log('Tableau des actualités trouvé : OK');
    } else {
      console.log('Tableau des actualités non trouvé : ÉCHEC');
      await browser.close();
      return;
    }

    // Récupérer le nombre d'actualités avant suppression
    const rowsBefore = await page.$$eval('table.table tbody tr', rows => rows.length);
    console.log(`Nombre d'actualités avant suppression : ${rowsBefore}`);

    if (rowsBefore === 0) {
      console.log('Aucune actualité à supprimer, test annulé.');
      await browser.close();
      return;
    }

    // Cliquer sur le bouton de suppression du premier élément
    await page.click('table.table tbody tr:first-child a.btn-danger');

    // Attendre la popup SweetAlert2
    await page.waitForSelector('.swal2-popup', { visible: true, timeout: 3000 });

    // Cliquer sur le bouton de confirmation "Oui, supprimer"
    await page.click('.swal2-confirm');

    // Attendre la navigation vers supprimer.php?id=...
    await page.waitForNavigation({ waitUntil: 'networkidle0' });

    // Retourner à la page index.php après suppression
    await page.goto(url, { waitUntil: 'networkidle0' });

    // Vérifier que le nombre d'actualités a diminué
    const rowsAfter = await page.$$eval('table.table tbody tr', rows => rows.length);
    console.log(`Nombre d'actualités après suppression : ${rowsAfter}`);

    if (rowsAfter === rowsBefore - 1) {
      console.log('Suppression d\'actualité réussie : OK');
    } else {
      console.log('Suppression d\'actualité échouée : ÉCHEC');
    }

  } catch (error) {
    console.error('Erreur lors du test de suppression e2e :', error);
  } finally {
    await browser.close();
  }
})();
