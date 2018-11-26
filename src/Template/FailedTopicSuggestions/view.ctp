<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FailedTopicSuggestion $failedTopicSuggestion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Failed Topic Suggestion'), ['action' => 'edit', $failedTopicSuggestion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Failed Topic Suggestion'), ['action' => 'delete', $failedTopicSuggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $failedTopicSuggestion->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Failed Topic Suggestions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Failed Topic Suggestion'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="failedTopicSuggestions view large-9 medium-8 columns content">
    <h3><?= h($failedTopicSuggestion->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Thesis Topic') ?></th>
            <td><?= $failedTopicSuggestion->has('thesis_topic') ? $this->Html->link($failedTopicSuggestion->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $failedTopicSuggestion->thesis_topic->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($failedTopicSuggestion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('New Topic By External Consultant') ?></th>
            <td><?= $failedTopicSuggestion->new_topic_by_external_consultant ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('New Topic By Head Of Department') ?></th>
            <td><?= $failedTopicSuggestion->new_topic_by_head_of_department ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Suggestion') ?></h4>
        <?= $this->Text->autoParagraph(h($failedTopicSuggestion->suggestion)); ?>
    </div>
</div>
