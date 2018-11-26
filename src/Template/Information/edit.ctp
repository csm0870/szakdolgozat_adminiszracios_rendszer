<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Information $information
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $information->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $information->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Information'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="information form large-9 medium-8 columns content">
    <?= $this->Form->create($information) ?>
    <fieldset>
        <legend><?= __('Edit Information') ?></legend>
        <?php
            echo $this->Form->control('filling_in_topic_form_begin_date', ['empty' => true]);
            echo $this->Form->control('filling_in_topic_form_end_date', ['empty' => true]);
            echo $this->Form->control('encryption_requlation');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
