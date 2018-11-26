<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Consultation[]|\Cake\Collection\CollectionInterface $consultations
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Consultation'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Consultation Occasions'), ['controller' => 'ConsultationOccasions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Consultation Occasion'), ['controller' => 'ConsultationOccasions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="consultations index large-9 medium-8 columns content">
    <h3><?= __('Consultations') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accepted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultations as $consultation): ?>
            <tr>
                <td><?= $this->Number->format($consultation->id) ?></td>
                <td><?= h($consultation->accepted) ?></td>
                <td><?= $consultation->has('thesis') ? $this->Html->link($consultation->thesis->id, ['controller' => 'Theses', 'action' => 'view', $consultation->thesis->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $consultation->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $consultation->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $consultation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultation->id)]) ?>
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
