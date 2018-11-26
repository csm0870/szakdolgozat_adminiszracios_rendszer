<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersReviewer $usersReviewer
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users Reviewer'), ['action' => 'edit', $usersReviewer->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Reviewer'), ['action' => 'delete', $usersReviewer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersReviewer->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Reviewers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Reviewer'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="usersReviewers view large-9 medium-8 columns content">
    <h3><?= h($usersReviewer->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $usersReviewer->has('user') ? $this->Html->link($usersReviewer->user->name, ['controller' => 'Users', 'action' => 'view', $usersReviewer->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reviewer') ?></th>
            <td><?= $usersReviewer->has('reviewer') ? $this->Html->link($usersReviewer->reviewer->name, ['controller' => 'Reviewers', 'action' => 'view', $usersReviewer->reviewer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($usersReviewer->id) ?></td>
        </tr>
    </table>
</div>
