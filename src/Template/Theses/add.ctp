<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Thesis $thesis
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reviews'), ['controller' => 'Reviews', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Review'), ['controller' => 'Reviews', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Consultations'), ['controller' => 'Consultations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Consultation'), ['controller' => 'Consultations', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="theses form large-9 medium-8 columns content">
    <?= $this->Form->create($thesis) ?>
    <fieldset>
        <legend><?= __('Add Thesis') ?></legend>
        <?php
            echo $this->Form->control('thesis_pdf');
            echo $this->Form->control('supplements');
            echo $this->Form->control('internal_consultant_grade');
            echo $this->Form->control('handed_in');
            echo $this->Form->control('accepted');
            echo $this->Form->control('deleted');
            echo $this->Form->control('review_id');
            echo $this->Form->control('thesis_topic_id', ['options' => $thesisTopics, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
