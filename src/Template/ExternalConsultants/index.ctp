<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExternalConsultant[]|\Cake\Collection\CollectionInterface $externalConsultants
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New External Consultant'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="externalConsultants index large-9 medium-8 columns content">
    <h3><?= __('External Consultants') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('workplace') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($externalConsultants as $externalConsultant): ?>
            <tr>
                <td><?= $this->Number->format($externalConsultant->id) ?></td>
                <td><?= h($externalConsultant->name) ?></td>
                <td><?= h($externalConsultant->workplace) ?></td>
                <td><?= h($externalConsultant->position) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $externalConsultant->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $externalConsultant->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $externalConsultant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $externalConsultant->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
