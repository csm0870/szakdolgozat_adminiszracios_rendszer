<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InternalConsultant[]|\Cake\Collection\CollectionInterface $internalConsultants
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="internalConsultants index large-9 medium-8 columns content">
    <h3><?= __('Internal Consultants') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('room_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rank') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('department_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($internalConsultants as $internalConsultant): ?>
            <tr>
                <td><?= $this->Number->format($internalConsultant->id) ?></td>
                <td><?= h($internalConsultant->room_number) ?></td>
                <td><?= h($internalConsultant->phone_number) ?></td>
                <td><?= h($internalConsultant->rank) ?></td>
                <td><?= h($internalConsultant->created) ?></td>
                <td><?= h($internalConsultant->modified) ?></td>
                <td><?= $internalConsultant->has('department') ? $this->Html->link($internalConsultant->department->name, ['controller' => 'Departments', 'action' => 'view', $internalConsultant->department->id]) : '' ?></td>
                <td><?= $internalConsultant->has('user') ? $this->Html->link($internalConsultant->user->name, ['controller' => 'Users', 'action' => 'view', $internalConsultant->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $internalConsultant->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $internalConsultant->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $internalConsultant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $internalConsultant->id)]) ?>
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
