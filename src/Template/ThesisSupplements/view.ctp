<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisSupplement $thesisSupplement
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Thesis Supplement'), ['action' => 'edit', $thesisSupplement->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Thesis Supplement'), ['action' => 'delete', $thesisSupplement->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesisSupplement->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Supplements'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Supplement'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="thesisSupplements view large-9 medium-8 columns content">
    <h3><?= h($thesisSupplement->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('File') ?></th>
            <td><?= h($thesisSupplement->file) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thesis Topic') ?></th>
            <td><?= $thesisSupplement->has('thesis_topic') ? $this->Html->link($thesisSupplement->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $thesisSupplement->thesis_topic->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($thesisSupplement->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($thesisSupplement->created) ?></td>
        </tr>
    </table>
</div>
