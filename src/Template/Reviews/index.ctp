<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review[]|\Cake\Collection\CollectionInterface $reviews
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Review'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="reviews index large-9 medium-8 columns content">
    <h3><?= __('Reviews') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('structure_and_style_point') ?></th>
                <th scope="col"><?= $this->Paginator->sort('processing_literature_point') ?></th>
                <th scope="col"><?= $this->Paginator->sort('writing_up_the_topic_point') ?></th>
                <th scope="col"><?= $this->Paginator->sort('practical applicability_point') ?></th>
                <th scope="col"><?= $this->Paginator->sort('grade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('confidentiality_contract') ?></th>
                <th scope="col"><?= $this->Paginator->sort('confidentiality_contract_accepted') ?></th>
                <th scope="col"><?= $this->Paginator->sort('thesis_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reviewer_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= $this->Number->format($review->id) ?></td>
                <td><?= $this->Number->format($review->structure_and_style_point) ?></td>
                <td><?= $this->Number->format($review->processing_literature_point) ?></td>
                <td><?= $this->Number->format($review->writing_up_the_topic_point) ?></td>
                <td><?= $this->Number->format($review->practical applicability_point) ?></td>
                <td><?= $this->Number->format($review->grade) ?></td>
                <td><?= h($review->confidentiality_contract) ?></td>
                <td><?= h($review->confidentiality_contract_accepted) ?></td>
                <td><?= $this->Number->format($review->thesis_id) ?></td>
                <td><?= $review->has('reviewer') ? $this->Html->link($review->reviewer->name, ['controller' => 'Reviewers', 'action' => 'view', $review->reviewer->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $review->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $review->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $review->id], ['confirm' => __('Are you sure you want to delete # {0}?', $review->id)]) ?>
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
