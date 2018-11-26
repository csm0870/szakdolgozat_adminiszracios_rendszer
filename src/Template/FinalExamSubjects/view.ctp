<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalExamSubject $finalExamSubject
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Final Exam Subject'), ['action' => 'edit', $finalExamSubject->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Final Exam Subject'), ['action' => 'delete', $finalExamSubject->id], ['confirm' => __('Are you sure you want to delete # {0}?', $finalExamSubject->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Final Exam Subjects'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Final Exam Subject'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="finalExamSubjects view large-9 medium-8 columns content">
    <h3><?= h($finalExamSubject->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($finalExamSubject->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Semester') ?></th>
            <td><?= h($finalExamSubject->semester) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Student') ?></th>
            <td><?= $finalExamSubject->has('student') ? $this->Html->link($finalExamSubject->student->name, ['controller' => 'Students', 'action' => 'view', $finalExamSubject->student->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($finalExamSubject->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Teachers') ?></h4>
        <?= $this->Text->autoParagraph(h($finalExamSubject->teachers)); ?>
    </div>
</div>
