<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reviewer[]|\Cake\Collection\CollectionInterface $reviewers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Reviewer'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reviews'), ['controller' => 'Reviews', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Review'), ['controller' => 'Reviews', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reviewers index large-9 medium-8 columns content">
    <h3><?= __('Reviewers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('workplace') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviewers as $reviewer): ?>
            <tr>
                <td><?= $this->Number->format($reviewer->id) ?></td>
                <td><?= h($reviewer->name) ?></td>
                <td><?= h($reviewer->workplace) ?></td>
                <td><?= h($reviewer->position) ?></td>
                <td><?= h($reviewer->created) ?></td>
                <td><?= h($reviewer->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $reviewer->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $reviewer->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $reviewer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reviewer->id)]) ?>
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
