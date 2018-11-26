<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersReviewer $usersReviewer
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $usersReviewer->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $usersReviewer->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users Reviewers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersReviewers form large-9 medium-8 columns content">
    <?= $this->Form->create($usersReviewer) ?>
    <fieldset>
        <legend><?= __('Edit Users Reviewer') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
            echo $this->Form->control('reviewer_id', ['options' => $reviewers, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
