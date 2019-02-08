<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisSupplement $thesisSupplement
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $thesisSupplement->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $thesisSupplement->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Thesis Supplements'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="thesisSupplements form large-9 medium-8 columns content">
    <?= $this->Form->create($thesisSupplement) ?>
    <fieldset>
        <legend><?= __('Edit Thesis Supplement') ?></legend>
        <?php
            echo $this->Form->control('file');
            echo $this->Form->control('thesis_topic_id', ['options' => $thesisTopics, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
