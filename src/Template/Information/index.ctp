<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Information[]|\Cake\Collection\CollectionInterface $information
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Information'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="information index large-9 medium-8 columns content">
    <h3><?= __('Information') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('filling_in_topic_form_begin_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('filling_in_topic_form_end_date') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($information as $information): ?>
            <tr>
                <td><?= $this->Number->format($information->id) ?></td>
                <td><?= h($information->filling_in_topic_form_begin_date) ?></td>
                <td><?= h($information->filling_in_topic_form_end_date) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $information->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $information->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $information->id], ['confirm' => __('Are you sure you want to delete # {0}?', $information->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
