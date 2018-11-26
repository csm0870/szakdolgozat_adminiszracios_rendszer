<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student[]|\Cake\Collection\CollectionInterface $students
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Student'), ['action' => 'add']) ?></li>
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
<div class="students index large-9 medium-8 columns content">
    <h3><?= __('Students') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('neptun') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('specialisation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('first_thesis_subject_completed') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('course_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('course_level_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('course_type_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $this->Number->format($student->id) ?></td>
                <td><?= h($student->name) ?></td>
                <td><?= h($student->address) ?></td>
                <td><?= h($student->neptun) ?></td>
                <td><?= h($student->email) ?></td>
                <td><?= h($student->phone_number) ?></td>
                <td><?= h($student->specialisation) ?></td>
                <td><?= h($student->first_thesis_subject_completed) ?></td>
                <td><?= h($student->created) ?></td>
                <td><?= h($student->modified) ?></td>
                <td><?= $student->has('course') ? $this->Html->link($student->course->name, ['controller' => 'Courses', 'action' => 'view', $student->course->id]) : '' ?></td>
                <td><?= $student->has('course_level') ? $this->Html->link($student->course_level->name, ['controller' => 'CourseLevels', 'action' => 'view', $student->course_level->id]) : '' ?></td>
                <td><?= $student->has('course_type') ? $this->Html->link($student->course_type->name, ['controller' => 'CourseTypes', 'action' => 'view', $student->course_type->id]) : '' ?></td>
                <td><?= $student->has('thesis') ? $this->Html->link($student->thesis->id, ['controller' => 'Theses', 'action' => 'view', $student->thesis->id]) : '' ?></td>
                <td><?= $student->has('user') ? $this->Html->link($student->user->name, ['controller' => 'Users', 'action' => 'view', $student->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $student->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $student->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $student->id], ['confirm' => __('Are you sure you want to delete # {0}?', $student->id)]) ?>
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
