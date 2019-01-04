<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= Configure::read('title')?></title>
        <?= $this->Html->meta('icon') ?>
        
        <?= $this->Html->css('/bootstrap_v4.1/css/bootstrap.min.css') ?>
        <?= $this->Html->css('/jquery-ui-1.12.1.custom/jquery-ui.min') ?>
        <?= $this->Html->css('/fontawesome-free-5.6.1-web/css/all.min') ?>
        <?= $this->Html->css('logged_in_page.css') ?>

        <?= $this->Html->script('popper.min.js') ?>
        <?= $this->Html->script('jquery-3.3.1.min.js') ?>
        <?= $this->Html->script('/jquery-ui-1.12.1.custom/jquery-ui.min') ?>
        <?= $this->Html->script('/fontawesome-free-5.6.1-web/js/all.min') ?>
        <?= $this->Html->script('/bootstrap_v4.1/js/bootstrap.min.js') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div class="main-content-wrapper">
            <div class="main-content">
                <?= $this->element('logged_in_page_header') ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </body>
</html>

