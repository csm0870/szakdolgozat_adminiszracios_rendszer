<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Consultation $consultation
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $consultation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $consultation->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Consultations'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Consultation Occasions'), ['controller' => 'ConsultationOccasions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Consultation Occasion'), ['controller' => 'ConsultationOccasions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="consultations form large-9 medium-8 columns content">
    <?= $this->Form->create($consultation) ?>
    <fieldset>
        <legend><?= __('Edit Consultation') ?></legend>
        <?php
            echo $this->Form->control('accepted');
            echo $this->Form->control('thesis_id', ['options' => $theses, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
