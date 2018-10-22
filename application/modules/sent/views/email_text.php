You've got mail!

<?php foreach ($_REQUEST as $key=>$value && $key != "ci_session" && strpos($key,'wp-') === false && $key != 'cc' && $key != 'bcc' && $key != 'hiddenInputSiteID' ) : ?>
<?php if (substr($key, 0, 1) != "_" && $key != "ci_session") : ?>
<?php echo $key?>: <?php echo $value; ?>

<?php endif; ?>
<?php endforeach; ?>