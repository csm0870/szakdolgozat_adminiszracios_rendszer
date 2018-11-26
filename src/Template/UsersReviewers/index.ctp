<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersReviewer[]|\Cake\Collection\CollectionInterface $usersReviewers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users Reviewer'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersReviewers index large-9 medium-8 columns content">
    <h3><?= __('Users Reviewers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reviewer_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersReviewers as $usersReviewer): ?>
            <tr>
                <td><?= $this->Number->format($usersReviewer->id) ?></td>
                <td><?= $usersReviewer->has('user') ? $this->Html->link($usersReviewer->user->name, ['controller' => 'Users', 'action' => 'view', $usersReviewer->user->id]) : '' ?></td>
                <td><?= $usersReviewer->has('reviewer') ? $this->Html->link($usersReviewer->reviewer->name, ['controller' => 'Reviewers', 'action' => 'view', $usersReviewer->reviewer->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $usersReviewer->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersReviewer->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersReviewer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersReviewer->id)]) ?>
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
