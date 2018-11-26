<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InternalConsultant $internalConsultant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $internalConsultant->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $internalConsultant->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="internalConsultants form large-9 medium-8 columns content">
    <?= $this->Form->create($internalConsultant) ?>
    <fieldset>
        <legend><?= __('Edit Internal Consultant') ?></legend>
        <?php
            echo $this->Form->control('room_number');
            echo $this->Form->control('phone_number');
            echo $this->Form->control('rank');
            echo $this->Form->control('department_id', ['options' => $departments, 'empty' => true]);
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
