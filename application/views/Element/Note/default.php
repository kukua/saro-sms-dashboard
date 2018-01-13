<?php ?>

<div class="note note-<?=$flash->params['class']?> padding-xs-vr">
    <strong> <?= h($flash->params['action']) ?></strong> <?= h($flash->params['message']) ?><br><br>
    <strong>TIP:</strong>  <?= h($flash->params['tip']) ?><br><br>
  <?= $this->Link->makeLink('<span class="btn-label icon fa '.$flash->params['icon'].'"></span>'.$flash->params['label'],$flash->params['url'],['class'=>'btn btn-success btn-labeled']) ?>
</div>