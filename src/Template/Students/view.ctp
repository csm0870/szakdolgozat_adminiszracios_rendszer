<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Student'), ['action' => 'edit', $student->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Student'), ['action' => 'delete', $student->id], ['confirm' => __('Are you sure you want to delete # {0}?', $student->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Students'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Student'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Courses'), ['controller' => 'Courses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Course'), ['controller' => 'Courses', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Course Levels'), ['controller' => 'CourseLevels', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Course Level'), ['controller' => 'CourseLevels', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Course Types'), ['controller' => 'CourseTypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Course Type'), ['controller' => 'CourseTypes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Final Exam Subjects'), ['controller' => 'FinalExamSubjects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Final Exam Subject'), ['controller' => 'FinalExamSubjects', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="students view large-9 medium-8 columns content">
    <h3><?= h($student->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($student->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($student->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Neptun') ?></th>
            <td><?= h($student->neptun) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($student->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone Number') ?></th>
            <td><?= h($student->phone_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Specialisation') ?></th>
            <td><?= h($student->specialisation) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course') ?></th>
            <td><?= $student->has('course') ? $this->Html->link($student->course->name, ['controller' => 'Courses', 'action' => 'view', $student->course->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Level') ?></th>
            <td><?= $student->has('course_level') ? $this->Html->link($student->course_level->name, ['controller' => 'CourseLevels', 'action' => 'view', $student->course_level->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Course Type') ?></th>
            <td><?= $student->has('course_type') ? $this->Html->link($student->course_type->name, ['controller' => 'CourseTypes', 'action' => 'view', $student->course_type->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thesis') ?></th>
            <td><?= $student->has('thesis') ? $this->Html->link($student->thesis->id, ['controller' => 'Theses', 'action' => 'view', $student->thesis->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $student->has('user') ? $this->Html->link($student->user->name, ['controller' => 'Users', 'action' => 'view', $student->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($student->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($student->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($student->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('First Thesis Subject Completed') ?></th>
            <td><?= $student->first_thesis_subject_completed ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Final Exam Subjects') ?></h4>
        <?php if (!empty($student->final_exam_subjects)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Semester') ?></th>
                <th scope="col"><?= __('Teachers') ?></th>
                <th scope="col"><?= __('Student Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($student->final_exam_subjects as $finalExamSubjects): ?>
            <tr>
                <td><?= h($finalExamSubjects->id) ?></td>
                <td><?= h($finalExamSubjects->name) ?></td>
                <td><?= h($finalExamSubjects->semester) ?></td>
                <td><?= h($finalExamSubjects->teachers) ?></td>
                <td><?= h($finalExamSubjects->student_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'FinalExamSubjects', 'action' => 'view', $finalExamSubjects->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'FinalExamSubjects', 'action' => 'edit', $finalExamSubjects->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'FinalExamSubjects', 'action' => 'delete', $finalExamSubjects->id], ['confirm' => __('Are you sure you want to delete # {0}?', $finalExamSubjects->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
