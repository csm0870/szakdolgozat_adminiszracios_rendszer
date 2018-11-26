<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ThesisTopic $thesisTopic
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Thesis Topic'), ['action' => 'edit', $thesisTopic->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Thesis Topic'), ['action' => 'delete', $thesisTopic->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesisTopic->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List External Consultants'), ['controller' => 'ExternalConsultants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New External Consultant'), ['controller' => 'ExternalConsultants', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['controller' => 'InternalConsultants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['controller' => 'InternalConsultants', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Failed Topic Suggestions'), ['controller' => 'FailedTopicSuggestions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Failed Topic Suggestion'), ['controller' => 'FailedTopicSuggestions', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Theses'), ['controller' => 'Theses', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis'), ['controller' => 'Theses', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="thesisTopics view large-9 medium-8 columns content">
    <h3><?= h($thesisTopic->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($thesisTopic->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Starting Semester') ?></th>
            <td><?= h($thesisTopic->starting_semester) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Language') ?></th>
            <td><?= h($thesisTopic->language) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('External Consultant') ?></th>
            <td><?= $thesisTopic->has('external_consultant') ? $this->Html->link($thesisTopic->external_consultant->name, ['controller' => 'ExternalConsultants', 'action' => 'view', $thesisTopic->external_consultant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Internal Consultant') ?></th>
            <td><?= $thesisTopic->has('internal_consultant') ? $this->Html->link($thesisTopic->internal_consultant->id, ['controller' => 'InternalConsultants', 'action' => 'view', $thesisTopic->internal_consultant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($thesisTopic->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thesis Type Id') ?></th>
            <td><?= $this->Number->format($thesisTopic->thesis_type_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($thesisTopic->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modeified') ?></th>
            <td><?= h($thesisTopic->modeified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accepted By Internal Consultant') ?></th>
            <td><?= $thesisTopic->accepted_by_internal_consultant ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accepted By Head Of Department') ?></th>
            <td><?= $thesisTopic->accepted_by_head_of_department ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accepted By External Consultant') ?></th>
            <td><?= $thesisTopic->accepted_by_external_consultant ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modifiable') ?></th>
            <td><?= $thesisTopic->modifiable ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $thesisTopic->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Thesis') ?></th>
            <td><?= $thesisTopic->is_thesis ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Encrytped') ?></th>
            <td><?= $thesisTopic->encrytped ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($thesisTopic->description)); ?>
    </div>
    <div class="row">
        <h4><?= __('Cause Of No External Consultant') ?></h4>
        <?= $this->Text->autoParagraph(h($thesisTopic->cause_of_no_external_consultant)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Failed Topic Suggestions') ?></h4>
        <?php if (!empty($thesisTopic->failed_topic_suggestions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Suggestion') ?></th>
                <th scope="col"><?= __('New Topic By External Consultant') ?></th>
                <th scope="col"><?= __('New Topic By Head Of Department') ?></th>
                <th scope="col"><?= __('Thesis Topic Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($thesisTopic->failed_topic_suggestions as $failedTopicSuggestions): ?>
            <tr>
                <td><?= h($failedTopicSuggestions->id) ?></td>
                <td><?= h($failedTopicSuggestions->suggestion) ?></td>
                <td><?= h($failedTopicSuggestions->new_topic_by_external_consultant) ?></td>
                <td><?= h($failedTopicSuggestions->new_topic_by_head_of_department) ?></td>
                <td><?= h($failedTopicSuggestions->thesis_topic_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'FailedTopicSuggestions', 'action' => 'view', $failedTopicSuggestions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'FailedTopicSuggestions', 'action' => 'edit', $failedTopicSuggestions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'FailedTopicSuggestions', 'action' => 'delete', $failedTopicSuggestions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $failedTopicSuggestions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Theses') ?></h4>
        <?php if (!empty($thesisTopic->theses)): ?>
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
            <?php foreach ($thesisTopic->theses as $theses): ?>
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
</div>
