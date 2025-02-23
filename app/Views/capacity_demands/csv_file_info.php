<div class="col-12 alert alert-dark">
    <h6 class="text-bold"><?= lang("Site.siteCSVFile") ?></h6>
    <h6 class="text-bold"><?= lang("Site.siteCSVFileInfo") ?></h6>
    <p><a class="alert-link" href="<?= $sampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadEmptyCSV") ?></a></p>
    <p><a class="alert-link" href="<?= $exampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadSample") ?></a></p>
    <p>
    <div><?= lang("Site.siteCSVHeaderInfo") ?></div>
    <div><?= lang("Site.siteCSVInfoHeader") ?>: <?= lang("Form.formDate") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formShift") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formKnowledge") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formKnowledgeLevel") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formAmount") ?></div>
    <div><span class="text-bold"><?= lang("Form.formDate") ?></span>: <?= lang("Site.siteCSVInfoDate") ?></div>
    <div><span class="text-bold"><?= lang("Form.formShift") ?></span>: <?= lang("Site.siteCSVInfoShift") ?></div>
    <div><span class="text-bold"><?= lang("Form.formKnowledge") ?></span>: <?= lang("Site.siteCSVInfoKnowledge") ?></div>
    <div><span class="text-bold"><?= lang("Form.formKnowledgeLevel") ?></span>: <?= lang("Site.siteCSVInfoKnowledgeLevel") ?></div>
    <div><span class="text-bold"><?= lang("Form.formAmount") ?></span>: <?= lang("Site.siteCSVInfoAmount") ?></div>
    </p>
</div>