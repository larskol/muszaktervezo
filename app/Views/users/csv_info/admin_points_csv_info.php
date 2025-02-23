<div class="col-12 alert alert-dark">
    <h6 class="text-bold"><?= lang("Site.siteCSVFile") ?></h6>
    <h6 class="text-bold"><?= lang("Site.siteCSVFileInfo") ?></h6>
    <p><a class="alert-link" href="<?= $sampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadEmptyCSV") ?></a></p>
    <p><a class="alert-link" href="<?= $exampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadSample") ?></a></p>
    <p>
    <div><?= lang("Site.siteCSVHeaderInfo") ?></div>
    <div><?= lang("Site.siteCSVInfoHeader") ?>: <?= lang("Form.formEmail") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formBonusPoint") ?></div>
    <div><span class="text-bold"><?= lang("Form.formEmail") ?></span>: <?= lang("Site.siteCSVInfoEmail") ?></div>
    <div><span class="text-bold"><?= lang("Form.formBonusPoint") ?></span>: <?= lang("Site.siteCSVInfoBonusPoint") ?></div>
    </p>
</div>