<div class="col-12 alert alert-dark">
    <h6 class="text-bold"><?= lang("Site.siteCSVFile") ?></h6>
    <h6 class="text-bold"><?= lang("Site.siteCSVFileInfo") ?></h6>
    <p><a class="alert-link" href="<?= $sampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadEmptyCSV") ?></a></p>
    <p><a class="alert-link" href="<?= $exampleUrl ?? '#' ?>"><?= lang("Site.siteDownloadSample") ?></a></p>
    <p>
    <div><?= lang("Site.siteCSVHeaderInfo") ?></div>
    <div><?= lang("Site.siteCSVInfoHeader") ?>: <?= lang("Form.formEmail") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formPassword") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formLastname") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formFirstname") ?><?= lang("Site.siteCSVDelimiter") ?><?php if(isset($role) && $role == "admin"): ?><?= lang("Form.formDepartment") ?><?= lang("Site.siteCSVDelimiter") ?><?php endif; ?><?= lang("Form.formWeeklyWorkHours") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formPaidAnnualLeave") ?><?= lang("Site.siteCSVDelimiter") ?><?= lang("Form.formBonusPoint") ?></div>
    <div><span class="text-bold"><?= lang("Form.formEmail") ?></span>: <?= lang("Site.siteCSVInfoEmail") ?></div>
    <div><span class="text-bold"><?= lang("Form.formPassword") ?></span>: <?= lang("Site.siteCSVInfoPassword") ?></div>
    <div><span class="text-bold"><?= lang("Form.formLastname") ?></span>: <?= lang("Site.siteCSVInfoName") ?></div>
    <div><span class="text-bold"><?= lang("Form.formFirstname") ?></span>: <?= lang("Site.siteCSVInfoName") ?></div>
    <?php if(isset($role) && $role == "admin"): ?>
    <div><span class="text-bold"><?= lang("Form.formDepartment") ?></span>: <?= lang("Site.siteCSVInfoDepartment") ?></div>
    <?php endif; ?>
    <div><span class="text-bold"><?= lang("Form.formWeeklyWorkHours") ?></span>: <?= lang("Site.siteCSVInfoRealNumber") ?></div>
    <div><span class="text-bold"><?= lang("Form.formPaidAnnualLeave") ?></span>: <?= lang("Site.siteCSVInfoRealNumber") ?></div>
    <div><span class="text-bold"><?= lang("Form.formBonusPoint") ?></span>: <?= lang("Site.siteCSVInfoBonusPoint") ?></div>
    </p>
</div>