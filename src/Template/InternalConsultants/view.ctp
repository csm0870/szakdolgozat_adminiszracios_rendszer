<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\InternalConsultant $internalConsultant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Internal Consultant'), ['action' => 'edit', $internalConsultant->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Internal Consultant'), ['action' => 'delete', $internalConsultant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $internalConsultant->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="internalConsultants view large-9 medium-8 columns content">
    <h3><?= h($internalConsultant->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Room Number') ?></th>
            <td><?= h($internalConsultant->room_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone Number') ?></th>
            <td><?= h($internalConsultant->phone_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rank') ?></th>
            <td><?= h($internalConsultant->rank) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Department') ?></th>
            <td><?= $internalConsultant->has('department') ? $this->Html->link($internalConsultant->department->name, ['controller' => 'Departments', 'action' => 'view', $internalConsultant->department->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $internalConsultant->has('user') ? $this->Html->link($internalConsultant->user->name, ['controller' => 'Users', 'action' => 'view', $internalConsultant->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($internalConsultant->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($internalConsultant->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($internalConsultant->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Thesis Topics') ?></h4>
        <?php if (!empty($internalConsultant->thesis_topics)): ?>
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
            <?php foreach ($internalConsultant->thesis_topics as $thesisTopics): ?>
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
