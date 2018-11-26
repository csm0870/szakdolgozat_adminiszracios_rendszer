<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Consultation $consultation
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Consultation'), ['action' => 'edit', $consultation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Consultation'), ['action' => 'delete', $consultation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Consultations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Consultation'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Consultation Occasions'), ['controller' => 'ConsultationOccasions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Consultation Occasion'), ['controller' => 'ConsultationOccasions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="consultations view large-9 medium-8 columns content">
    <h3><?= h($consultation->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Thesis') ?></th>
            <td><?= $consultation->has('thesis') ? $this->Html->link($consultation->thesis->id, ['controller' => 'Theses', 'action' => 'view', $consultation->thesis->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($consultation->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accepted') ?></th>
            <td><?= $consultation->accepted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Consultation Occasions') ?></h4>
        <?php if (!empty($consultation->consultation_occasions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Date') ?></th>
                <th scope="col"><?= __('Activity') ?></th>
                <th scope="col"><?= __('Consultation Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($consultation->consultation_occasions as $consultationOccasions): ?>
            <tr>
                <td><?= h($consultationOccasions->id) ?></td>
                <td><?= h($consultationOccasions->date) ?></td>
                <td><?= h($consultationOccasions->activity) ?></td>
                <td><?= h($consultationOccasions->consultation_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ConsultationOccasions', 'action' => 'view', $consultationOccasions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ConsultationOccasions', 'action' => 'edit', $consultationOccasions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ConsultationOccasions', 'action' => 'delete', $consultationOccasions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultationOccasions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
