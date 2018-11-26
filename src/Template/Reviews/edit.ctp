<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $review->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $review->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Reviews'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reviews form large-9 medium-8 columns content">
    <?= $this->Form->create($review) ?>
    <fieldset>
        <legend><?= __('Edit Review') ?></legend>
        <?php
            echo $this->Form->control('structure_and_style_point');
            echo $this->Form->control('cause_of_structure_and_style_point');
            echo $this->Form->control('processing_literature_point');
            echo $this->Form->control('cause_of_processing_literature_point');
            echo $this->Form->control('writing_up_the_topic_point');
            echo $this->Form->control('cause_writing_up_the_topic_point');
            echo $this->Form->control('practical applicability_point');
            echo $this->Form->control('cause_of_practical applicability');
            echo $this->Form->control('general_comments');
            echo $this->Form->control('grade');
            echo $this->Form->control('confidentiality_contract');
            echo $this->Form->control('confidentiality_contract_accepted');
            echo $this->Form->control('thesis_id');
            echo $this->Form->control('reviewer_id', ['options' => $reviewers, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
