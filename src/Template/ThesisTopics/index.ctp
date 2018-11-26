<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisTopic[]|\Cake\Collection\CollectionInterface $thesisTopics
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List External Consultants'), ['controller' => 'ExternalConsultants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New External Consultant'), ['controller' => 'ExternalConsultants', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['controller' => 'InternalConsultants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['controller' => 'InternalConsultants', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Failed Topic Suggestions'), ['controller' => 'FailedTopicSuggestions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Failed Topic Suggestion'), ['controller' => 'FailedTopicSuggestions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="thesisTopics index large-9 medium-8 columns content">
    <h3><?= __('Thesis Topics') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('starting_semester') ?></th>
                <th scope="col"><?= $this->Paginator->sort('language') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modeified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accepted_by_internal_consultant') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accepted_by_head_of_department') ?></th>
                <th scope="col"><?= $this->Paginator->sort('accepted_by_external_consultant') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modifiable') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deleted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_thesis') ?></th>
                <th scope="col"><?= $this->Paginator->sort('external_consultant_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('internal_consultant_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('encrytped') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_type_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($thesisTopics as $thesisTopic): ?>
            <tr>
                <td><?= $this->Number->format($thesisTopic->id) ?></td>
                <td><?= h($thesisTopic->title) ?></td>
                <td><?= h($thesisTopic->starting_semester) ?></td>
                <td><?= h($thesisTopic->language) ?></td>
                <td><?= h($thesisTopic->created) ?></td>
                <td><?= h($thesisTopic->modeified) ?></td>
                <td><?= h($thesisTopic->accepted_by_internal_consultant) ?></td>
                <td><?= h($thesisTopic->accepted_by_head_of_department) ?></td>
                <td><?= h($thesisTopic->accepted_by_external_consultant) ?></td>
                <td><?= h($thesisTopic->modifiable) ?></td>
                <td><?= h($thesisTopic->deleted) ?></td>
                <td><?= h($thesisTopic->is_thesis) ?></td>
                <td><?= $thesisTopic->has('external_consultant') ? $this->Html->link($thesisTopic->external_consultant->name, ['controller' => 'ExternalConsultants', 'action' => 'view', $thesisTopic->external_consultant->id]) : '' ?></td>
                <td><?= $thesisTopic->has('internal_consultant') ? $this->Html->link($thesisTopic->internal_consultant->id, ['controller' => 'InternalConsultants', 'action' => 'view', $thesisTopic->internal_consultant->id]) : '' ?></td>
                <td><?= h($thesisTopic->encrytped) ?></td>
                <td><?= $this->Number->format($thesisTopic->thesis_type_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $thesisTopic->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $thesisTopic->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $thesisTopic->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesisTopic->id)]) ?>
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
