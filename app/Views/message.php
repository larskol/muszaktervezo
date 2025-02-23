<div class='col-12 alert alert-<?= $type ?? 'dark' ?> alert-dismissible' role='alert'>
    <div><?= $message ?? '' ?></div>
    <?php if(isset($errors) && is_array($errors)): foreach($errors as $key => $error): ?>
        <div>
            <?= $error ?>
        </div>
    <?php endforeach; endif; ?>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'><?= lang("Icons.iconAlertDismiss") ?></span>
    </button>
</div>