<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConsultationOccasion $consultationOccasion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $consultationOccasion->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $consultationOccasion->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Consultation Occasions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Consultations'), ['controller' => 'Consultations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Consultation'), ['controller' => 'Consultations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="consultationOccasions form large-9 medium-8 columns content">
    <?= $this->Form->create($consultationOccasion) ?>
    <fieldset>
        <legend><?= __('Edit Consultation Occasion') ?></legend>
        <?php
            echo $this->Form->control('date', ['empty' => true]);
            echo $this->Form->control('activity');
            echo $this->Form->control('consultation_id', ['options' => $consultations, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
