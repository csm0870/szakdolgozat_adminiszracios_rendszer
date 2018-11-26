<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExternalConsultant $externalConsultant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit External Consultant'), ['action' => 'edit', $externalConsultant->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete External Consultant'), ['action' => 'delete', $externalConsultant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $externalConsultant->id)]) ?> </li>
        <li><?= $this->Html->link(__('List External Consultants'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New External Consultant'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="externalConsultants view large-9 medium-8 columns content">
    <h3><?= h($externalConsultant->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($externalConsultant->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Workplace') ?></th>
            <td><?= h($externalConsultant->workplace) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= h($externalConsultant->position) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($externalConsultant->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Thesis Topics') ?></h4>
        <?php if (!empty($externalConsultant->thesis_topics)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Starting Semester') ?></th>
                <th scope="col"><?= __('Language') ?></th>
                <th scope="col"><?= __('Cause Of No External Consultant') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modeified') ?></th>
                <th scope="col"><?= __('Accepted By Internal Consultant') ?></th>
                <th scope="col"><?= __('Accepted By Head Of Department') ?></th>
                <th scope="col"><?= __('Accepted By External Consultant') ?></th>
                <th scope="col"><?= __('Modifiable') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col"><?= __('Is Thesis') ?></th>
                <th scope="col"><?= __('External Consultant Id') ?></th>
                <th scope="col"><?= __('Internal Consultant Id') ?></th>
                <th scope="col"><?= __('Encrytped') ?></th>
                <th scope="col"><?= __('Thesis Type Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($externalConsultant->thesis_topics as $thesisTopics): ?>
            <tr>
                <td><?= h($thesisTopics->id) ?></td>
                <td><?= h($thesisTopics->title) ?></td>
                <td><?= h($thesisTopics->description) ?></td>
                <td><?= h($thesisTopics->starting_semester) ?></td>
                <td><?= h($thesisTopics->language) ?></td>
                <td><?= h($thesisTopics->cause_of_no_external_consultant) ?></td>
                <td><?= h($thesisTopics->created) ?></td>
                <td><?= h($thesisTopics->modeified) ?></td>
                <td><?= h($thesisTopics->accepted_by_internal_consultant) ?></td>
                <td><?= h($thesisTopics->accepted_by_head_of_department) ?></td>
                <td><?= h($thesisTopics->accepted_by_external_consultant) ?></td>
                <td><?= h($thesisTopics->modifiable) ?></td>
                <td><?= h($thesisTopics->deleted) ?></td>
                <td><?= h($thesisTopics->is_thesis) ?></td>
                <td><?= h($thesisTopics->external_consultant_id) ?></td>
                <td><?= h($thesisTopics->internal_consultant_id) ?></td>
                <td><?= h($thesisTopics->encrytped) ?></td>
                <td><?= h($thesisTopics->thesis_type_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ThesisTopics', 'action' => 'view', $thesisTopics->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopics->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ThesisTopics', 'action' => 'delete', $thesisTopics->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesisTopics->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
