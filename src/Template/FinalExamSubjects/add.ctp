<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalExamSubject $finalExamSubject
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Final Exam Subjects'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="finalExamSubjects form large-9 medium-8 columns content">
    <?= $this->Form->create($finalExamSubject) ?>
    <fieldset>
        <legend><?= __('Add Final Exam Subject') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('semester');
            echo $this->Form->control('teachers');
            echo $this->Form->control('student_id', ['options' => $students, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
