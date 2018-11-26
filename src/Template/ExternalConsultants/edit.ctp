<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExternalConsultant $externalConsultant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $externalConsultant->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $externalConsultant->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List External Consultants'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="externalConsultants form large-9 medium-8 columns content">
    <?= $this->Form->create($externalConsultant) ?>
    <fieldset>
        <legend><?= __('Edit External Consultant') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('workplace');
            echo $this->Form->control('position');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
