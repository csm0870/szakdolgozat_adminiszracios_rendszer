<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CourseType $courseType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $courseType->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $courseType->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Course Types'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="courseTypes form large-9 medium-8 columns content">
    <?= $this->Form->create($courseType) ?>
    <fieldset>
        <legend><?= __('Edit Course Type') ?></legend>
        <?php
            echo $this->Form->control('name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
