<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisSupplement[]|\Cake\Collection\CollectionInterface $thesisSupplements
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Thesis Supplement'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="thesisSupplements index large-9 medium-8 columns content">
    <h3><?= __('Thesis Supplements') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('file') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_topic_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($thesisSupplements as $thesisSupplement): ?>
            <tr>
                <td><?= $this->Number->format($thesisSupplement->id) ?></td>
                <td><?= h($thesisSupplement->file) ?></td>
                <td><?= $thesisSupplement->has('thesis_topic') ? $this->Html->link($thesisSupplement->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $thesisSupplement->thesis_topic->id]) : '' ?></td>
                <td><?= h($thesisSupplement->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $thesisSupplement->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $thesisSupplement->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $thesisSupplement->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesisSupplement->id)]) ?>
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
