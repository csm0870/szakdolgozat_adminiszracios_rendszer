<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisTopic $thesisTopic
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $thesisTopic->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $thesisTopic->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['action' => 'index']) ?></li>
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
<div class="thesisTopics form large-9 medium-8 columns content">
    <?= $this->Form->create($thesisTopic) ?>
    <fieldset>
        <legend><?= __('Edit Thesis Topic') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('description');
            echo $this->Form->control('starting_semester');
            echo $this->Form->control('language');
            echo $this->Form->control('cause_of_no_external_consultant');
            echo $this->Form->control('modeified', ['empty' => true]);
            echo $this->Form->control('accepted_by_internal_consultant');
            echo $this->Form->control('accepted_by_head_of_department');
            echo $this->Form->control('accepted_by_external_consultant');
            echo $this->Form->control('modifiable');
            echo $this->Form->control('deleted');
            echo $this->Form->control('is_thesis');
            echo $this->Form->control('external_consultant_id', ['options' => $externalConsultants, 'empty' => true]);
            echo $this->Form->control('internal_consultant_id', ['options' => $internalConsultants, 'empty' => true]);
            echo $this->Form->control('encrytped');
            echo $this->Form->control('thesis_type_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
