<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CourseLevel $courseLevel
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Course Level'), ['action' => 'edit', $courseLevel->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Course Level'), ['action' => 'delete', $courseLevel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courseLevel->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Course Levels'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Course Level'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="courseLevels view large-9 medium-8 columns content">
    <h3><?= h($courseLevel->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($courseLevel->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($courseLevel->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Students') ?></h4>
        <?php if (!empty($courseLevel->students)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('Neptun') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Specialisation') ?></th>
                <th scope="col"><?= __('First Thesis Subject Completed') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Course Id') ?></th>
                <th scope="col"><?= __('Course Level Id') ?></th>
                <th scope="col"><?= __('Course Type Id') ?></th>
                <th scope="col"><?= __('Thesis Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($courseLevel->students as $students): ?>
            <tr>
                <td><?= h($students->id) ?></td>
                <td><?= h($students->name) ?></td>
                <td><?= h($students->address) ?></td>
                <td><?= h($students->neptun) ?></td>
                <td><?= h($students->email) ?></td>
                <td><?= h($students->phone_number) ?></td>
                <td><?= h($students->specialisation) ?></td>
                <td><?= h($students->first_thesis_subject_completed) ?></td>
                <td><?= h($students->created) ?></td>
                <td><?= h($students->modified) ?></td>
                <td><?= h($students->course_id) ?></td>
                <td><?= h($students->course_level_id) ?></td>
                <td><?= h($students->course_type_id) ?></td>
                <td><?= h($students->thesis_id) ?></td>
                <td><?= h($students->user_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
