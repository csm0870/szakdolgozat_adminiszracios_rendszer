<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Students'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Courses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Course'), ['controller' => 'Courses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Course Levels'), ['controller' => 'CourseLevels', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Course Level'), ['controller' => 'CourseLevels', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Course Types'), ['controller' => 'CourseTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Course Type'), ['controller' => 'CourseTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Final Exam Subjects'), ['controller' => 'FinalExamSubjects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Final Exam Subject'), ['controller' => 'FinalExamSubjects', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="students form large-9 medium-8 columns content">
    <?= $this->Form->create($student) ?>
    <fieldset>
        <legend><?= __('Add Student') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('address');
            echo $this->Form->control('neptun');
            echo $this->Form->control('email');
            echo $this->Form->control('phone_number');
            echo $this->Form->control('specialisation');
            echo $this->Form->control('first_thesis_subject_completed');
            echo $this->Form->control('course_id', ['options' => $courses, 'empty' => true]);
            echo $this->Form->control('course_level_id', ['options' => $courseLevels, 'empty' => true]);
            echo $this->Form->control('course_type_id', ['options' => $courseTypes, 'empty' => true]);
            echo $this->Form->control('thesis_id', ['options' => $theses, 'empty' => true]);
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
