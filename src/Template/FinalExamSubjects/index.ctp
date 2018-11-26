<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FinalExamSubject[]|\Cake\Collection\CollectionInterface $finalExamSubjects
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Final Exam Subject'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="finalExamSubjects index large-9 medium-8 columns content">
    <h3><?= __('Final Exam Subjects') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('semester') ?></th>
                <th scope="col"><?= $this->Paginator->sort('student_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($finalExamSubjects as $finalExamSubject): ?>
            <tr>
                <td><?= $this->Number->format($finalExamSubject->id) ?></td>
                <td><?= h($finalExamSubject->name) ?></td>
                <td><?= h($finalExamSubject->semester) ?></td>
                <td><?= $finalExamSubject->has('student') ? $this->Html->link($finalExamSubject->student->name, ['controller' => 'Students', 'action' => 'view', $finalExamSubject->student->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $finalExamSubject->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $finalExamSubject->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $finalExamSubject->id], ['confirm' => __('Are you sure you want to delete # {0}?', $finalExamSubject->id)]) ?>
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
