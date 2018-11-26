<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConsultationOccasion[]|\Cake\Collection\CollectionInterface $consultationOccasions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Consultation Occasion'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Consultations'), ['controller' => 'Consultations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Consultation'), ['controller' => 'Consultations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="consultationOccasions index large-9 medium-8 columns content">
    <h3><?= __('Consultation Occasions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('consultation_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultationOccasions as $consultationOccasion): ?>
            <tr>
                <td><?= $this->Number->format($consultationOccasion->id) ?></td>
                <td><?= h($consultationOccasion->date) ?></td>
                <td><?= $consultationOccasion->has('consultation') ? $this->Html->link($consultationOccasion->consultation->id, ['controller' => 'Consultations', 'action' => 'view', $consultationOccasion->consultation->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $consultationOccasion->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $consultationOccasion->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $consultationOccasion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultationOccasion->id)]) ?>
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
