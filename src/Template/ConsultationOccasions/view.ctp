<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConsultationOccasion $consultationOccasion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Consultation Occasion'), ['action' => 'edit', $consultationOccasion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Consultation Occasion'), ['action' => 'delete', $consultationOccasion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultationOccasion->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Consultation Occasions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Consultation Occasion'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Consultations'), ['controller' => 'Consultations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Consultation'), ['controller' => 'Consultations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="consultationOccasions view large-9 medium-8 columns content">
    <h3><?= h($consultationOccasion->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Consultation') ?></th>
            <td><?= $consultationOccasion->has('consultation') ? $this->Html->link($consultationOccasion->consultation->id, ['controller' => 'Consultations', 'action' => 'view', $consultationOccasion->consultation->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($consultationOccasion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($consultationOccasion->date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Activity') ?></h4>
        <?= $this->Text->autoParagraph(h($consultationOccasion->activity)); ?>
    </div>
</div>
