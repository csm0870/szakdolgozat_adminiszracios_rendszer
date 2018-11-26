<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FailedTopicSuggestion $failedTopicSuggestion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $failedTopicSuggestion->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $failedTopicSuggestion->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Failed Topic Suggestions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="failedTopicSuggestions form large-9 medium-8 columns content">
    <?= $this->Form->create($failedTopicSuggestion) ?>
    <fieldset>
        <legend><?= __('Edit Failed Topic Suggestion') ?></legend>
        <?php
            echo $this->Form->control('suggestion');
            echo $this->Form->control('new_topic_by_external_consultant');
            echo $this->Form->control('new_topic_by_head_of_department');
            echo $this->Form->control('thesis_topic_id', ['options' => $thesisTopics, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
