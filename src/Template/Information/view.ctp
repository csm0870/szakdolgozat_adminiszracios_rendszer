<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Information $information
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Information'), ['action' => 'edit', $information->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Information'), ['action' => 'delete', $information->id], ['confirm' => __('Are you sure you want to delete # {0}?', $information->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Information'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Information'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="information view large-9 medium-8 columns content">
    <h3><?= h($information->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($information->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Filling In Topic Form Begin Date') ?></th>
            <td><?= h($information->filling_in_topic_form_begin_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Filling In Topic Form End Date') ?></th>
            <td><?= h($information->filling_in_topic_form_end_date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Encryption Requlation') ?></h4>
        <?= $this->Text->autoParagraph(h($information->encryption_requlation)); ?>
    </div>
</div>
