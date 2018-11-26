<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Thesis[]|\Cake\Collection\CollectionInterface $theses
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['action' => 'add']) ?></li>
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
<div class="theses index large-9 medium-8 columns content">
    <h3><?= __('Theses') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_pdf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('supplements') ?></th>
                <th scope="col"><?= $this->Paginator->sort('internal_consultant_grade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('handed_in') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accepted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('review_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_topic_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($theses as $thesis): ?>
            <tr>
                <td><?= $this->Number->format($thesis->id) ?></td>
                <td><?= h($thesis->thesis_pdf) ?></td>
                <td><?= h($thesis->supplements) ?></td>
                <td><?= $this->Number->format($thesis->internal_consultant_grade) ?></td>
                <td><?= h($thesis->handed_in) ?></td>
                <td><?= h($thesis->accepted) ?></td>
                <td><?= h($thesis->deleted) ?></td>
                <td><?= $this->Number->format($thesis->review_id) ?></td>
                <td><?= $thesis->has('thesis_topic') ? $this->Html->link($thesis->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $thesis->thesis_topic->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $thesis->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $thesis->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $thesis->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesis->id)]) ?>
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
