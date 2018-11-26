<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FailedTopicSuggestion[]|\Cake\Collection\CollectionInterface $failedTopicSuggestions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Failed Topic Suggestion'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="failedTopicSuggestions index large-9 medium-8 columns content">
    <h3><?= __('Failed Topic Suggestions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('new_topic_by_external_consultant') ?></th>
                <th scope="col"><?= $this->Paginator->sort('new_topic_by_head_of_department') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_topic_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($failedTopicSuggestions as $failedTopicSuggestion): ?>
            <tr>
                <td><?= $this->Number->format($failedTopicSuggestion->id) ?></td>
                <td><?= h($failedTopicSuggestion->new_topic_by_external_consultant) ?></td>
                <td><?= h($failedTopicSuggestion->new_topic_by_head_of_department) ?></td>
                <td><?= $failedTopicSuggestion->has('thesis_topic') ? $this->Html->link($failedTopicSuggestion->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $failedTopicSuggestion->thesis_topic->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $failedTopicSuggestion->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $failedTopicSuggestion->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $failedTopicSuggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $failedTopicSuggestion->id)]) ?>
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
