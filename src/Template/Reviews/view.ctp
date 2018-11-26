<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Review $review
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Review'), ['action' => 'edit', $review->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Review'), ['action' => 'delete', $review->id], ['confirm' => __('Are you sure you want to delete # {0}?', $review->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Reviews'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Review'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Questions'), ['controller' => 'Questions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Question'), ['controller' => 'Questions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="reviews view large-9 medium-8 columns content">
    <h3><?= h($review->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Confidentiality Contract') ?></th>
            <td><?= h($review->confidentiality_contract) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reviewer') ?></th>
            <td><?= $review->has('reviewer') ? $this->Html->link($review->reviewer->name, ['controller' => 'Reviewers', 'action' => 'view', $review->reviewer->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($review->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Structure And Style Point') ?></th>
            <td><?= $this->Number->format($review->structure_and_style_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Processing Literature Point') ?></th>
            <td><?= $this->Number->format($review->processing_literature_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Writing Up The Topic Point') ?></th>
            <td><?= $this->Number->format($review->writing_up_the_topic_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Practical Applicability Point') ?></th>
            <td><?= $this->Number->format($review->practical applicability_point) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Grade') ?></th>
            <td><?= $this->Number->format($review->grade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thesis Id') ?></th>
            <td><?= $this->Number->format($review->thesis_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Confidentiality Contract Accepted') ?></th>
            <td><?= $review->confidentiality_contract_accepted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Cause Of Structure And Style Point') ?></h4>
        <?= $this->Text->autoParagraph(h($review->cause_of_structure_and_style_point)); ?>
    </div>
    <div class="row">
        <h4><?= __('Cause Of Processing Literature Point') ?></h4>
        <?= $this->Text->autoParagraph(h($review->cause_of_processing_literature_point)); ?>
    </div>
    <div class="row">
        <h4><?= __('Cause Writing Up The Topic Point') ?></h4>
        <?= $this->Text->autoParagraph(h($review->cause_writing_up_the_topic_point)); ?>
    </div>
    <div class="row">
        <h4><?= __('Cause Of Practical Applicability') ?></h4>
        <?= $this->Text->autoParagraph(h($review->cause_of_practical applicability)); ?>
    </div>
    <div class="row">
        <h4><?= __('General Comments') ?></h4>
        <?= $this->Text->autoParagraph(h($review->general_comments)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Theses') ?></h4>
        <?php if (!empty($review->theses)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Thesis Pdf') ?></th>
                <th scope="col"><?= __('Supplements') ?></th>
                <th scope="col"><?= __('Internal Consultant Grade') ?></th>
                <th scope="col"><?= __('Handed In') ?></th>
                <th scope="col"><?= __('Accepted') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col"><?= __('Review Id') ?></th>
                <th scope="col"><?= __('Thesis Topic Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($review->theses as $theses): ?>
            <tr>
                <td><?= h($theses->id) ?></td>
                <td><?= h($theses->thesis_pdf) ?></td>
                <td><?= h($theses->supplements) ?></td>
                <td><?= h($theses->internal_consultant_grade) ?></td>
                <td><?= h($theses->handed_in) ?></td>
                <td><?= h($theses->accepted) ?></td>
                <td><?= h($theses->deleted) ?></td>
                <td><?= h($theses->review_id) ?></td>
                <td><?= h($theses->thesis_topic_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Theses', 'action' => 'view', $theses->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Theses', 'action' => 'edit', $theses->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Theses', 'action' => 'delete', $theses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $theses->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Questions') ?></h4>
        <?php if (!empty($review->questions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Question') ?></th>
                <th scope="col"><?= __('Review Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($review->questions as $questions): ?>
            <tr>
                <td><?= h($questions->id) ?></td>
                <td><?= h($questions->question) ?></td>
                <td><?= h($questions->review_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Questions', 'action' => 'view', $questions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Questions', 'action' => 'edit', $questions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Questions', 'action' => 'delete', $questions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $questions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
