<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Department'), ['action' => 'edit', $department->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Department'), ['action' => 'delete', $department->id], ['confirm' => __('Are you sure you want to delete # {0}?', $department->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['controller' => 'InternalConsultants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['controller' => 'InternalConsultants', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($department->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($department->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Internal Consultants') ?></h4>
        <?php if (!empty($department->internal_consultants)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Room Number') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Rank') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Department Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($department->internal_consultants as $internalConsultants): ?>
            <tr>
                <td><?= h($internalConsultants->id) ?></td>
                <td><?= h($internalConsultants->room_number) ?></td>
                <td><?= h($internalConsultants->phone_number) ?></td>
                <td><?= h($internalConsultants->rank) ?></td>
                <td><?= h($internalConsultants->created) ?></td>
                <td><?= h($internalConsultants->modified) ?></td>
                <td><?= h($internalConsultants->department_id) ?></td>
                <td><?= h($internalConsultants->user_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InternalConsultants', 'action' => 'view', $internalConsultants->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InternalConsultants', 'action' => 'edit', $internalConsultants->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InternalConsultants', 'action' => 'delete', $internalConsultants->id], ['confirm' => __('Are you sure you want to delete # {0}?', $internalConsultants->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
