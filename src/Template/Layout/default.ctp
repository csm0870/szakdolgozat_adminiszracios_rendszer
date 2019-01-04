<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= Configure::read('title')?></title>
        <?= $this->Html->meta('icon') ?>
        
        <?= $this->Html->css('/bootstrap_v4.1/css/bootstrap.min.css') ?>
        <?= $this->Html->css('main.css') ?>

        <?= $this->Html->script('jquery-3.3.1.min.js') ?>
        <?= $this->Html->script('popper.min.js') ?>
        <?= $this->Html->script('/bootstrap_v4.1/js/bootstrap.min.js') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div class="main-content-wrapper">
            <div class="main-content">
                <?= $this->element('header') ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </body>
</html>
