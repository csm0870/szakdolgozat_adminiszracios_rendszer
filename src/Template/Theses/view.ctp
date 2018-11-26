<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Thesis $thesis
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Thesis'), ['action' => 'edit', $thesis->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Thesis'), ['action' => 'delete', $thesis->id], ['confirm' => __('Are you sure you want to delete # {0}?', $thesis->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Theses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Thesis Topics'), ['controller' => 'ThesisTopics', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Thesis Topic'), ['controller' => 'ThesisTopics', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reviews'), ['controller' => 'Reviews', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Review'), ['controller' => 'Reviews', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Consultations'), ['controller' => 'Consultations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Consultation'), ['controller' => 'Consultations', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="theses view large-9 medium-8 columns content">
    <h3><?= h($thesis->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Thesis Pdf') ?></th>
            <td><?= h($thesis->thesis_pdf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Supplements') ?></th>
            <td><?= h($thesis->supplements) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Thesis Topic') ?></th>
            <td><?= $thesis->has('thesis_topic') ? $this->Html->link($thesis->thesis_topic->title, ['controller' => 'ThesisTopics', 'action' => 'view', $thesis->thesis_topic->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($thesis->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Internal Consultant Grade') ?></th>
            <td><?= $this->Number->format($thesis->internal_consultant_grade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Review Id') ?></th>
            <td><?= $this->Number->format($thesis->review_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Handed In') ?></th>
            <td><?= $thesis->handed_in ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Accepted') ?></th>
            <td><?= $thesis->accepted ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $thesis->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Reviews') ?></h4>
        <?php if (!empty($thesis->reviews)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Structure And Style Point') ?></th>
                <th scope="col"><?= __('Cause Of Structure And Style Point') ?></th>
                <th scope="col"><?= __('Processing Literature Point') ?></th>
                <th scope="col"><?= __('Cause Of Processing Literature Point') ?></th>
                <th scope="col"><?= __('Writing Up The Topic Point') ?></th>
                <th scope="col"><?= __('Cause Writing Up The Topic Point') ?></th>
                <th scope="col"><?= __('Practical Applicability Point') ?></th>
                <th scope="col"><?= __('Cause Of Practical Applicability') ?></th>
                <th scope="col"><?= __('General Comments') ?></th>
                <th scope="col"><?= __('Grade') ?></th>
                <th scope="col"><?= __('Confidentiality Contract') ?></th>
                <th scope="col"><?= __('Confidentiality Contract Accepted') ?></th>
                <th scope="col"><?= __('Thesis Id') ?></th>
                <th scope="col"><?= __('Reviewer Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($thesis->reviews as $reviews): ?>
            <tr>
                <td><?= h($reviews->id) ?></td>
                <td><?= h($reviews->structure_and_style_point) ?></td>
                <td><?= h($reviews->cause_of_structure_and_style_point) ?></td>
                <td><?= h($reviews->processing_literature_point) ?></td>
                <td><?= h($reviews->cause_of_processing_literature_point) ?></td>
                <td><?= h($reviews->writing_up_the_topic_point) ?></td>
                <td><?= h($reviews->cause_writing_up_the_topic_point) ?></td>
                <td><?= h($reviews->practical applicability_point) ?></td>
                <td><?= h($reviews->cause_of_practical applicability) ?></td>
                <td><?= h($reviews->general_comments) ?></td>
                <td><?= h($reviews->grade) ?></td>
                <td><?= h($reviews->confidentiality_contract) ?></td>
                <td><?= h($reviews->confidentiality_contract_accepted) ?></td>
                <td><?= h($reviews->thesis_id) ?></td>
                <td><?= h($reviews->reviewer_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Reviews', 'action' => 'view', $reviews->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Reviews', 'action' => 'edit', $reviews->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Reviews', 'action' => 'delete', $reviews->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reviews->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Consultations') ?></h4>
        <?php if (!empty($thesis->consultations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Accepted') ?></th>
                <th scope="col"><?= __('Thesis Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($thesis->consultations as $consultations): ?>
            <tr>
                <td><?= h($consultations->id) ?></td>
                <td><?= h($consultations->accepted) ?></td>
                <td><?= h($consultations->thesis_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Consultations', 'action' => 'view', $consultations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Consultations', 'action' => 'edit', $consultations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Consultations', 'action' => 'delete', $consultations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $consultations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Students') ?></h4>
        <?php if (!empty($thesis->students)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Address') ?></th>
                <th scope="col"><?= __('Neptun') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Specialisation') ?></th>
                <th scope="col"><?= __('First Thesis Subject Completed') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Course Id') ?></th>
                <th scope="col"><?= __('Course Level Id') ?></th>
                <th scope="col"><?= __('Course Type Id') ?></th>
                <th scope="col"><?= __('Thesis Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($thesis->students as $students): ?>
            <tr>
                <td><?= h($students->id) ?></td>
                <td><?= h($students->name) ?></td>
                <td><?= h($students->address) ?></td>
                <td><?= h($students->neptun) ?></td>
                <td><?= h($students->email) ?></td>
                <td><?= h($students->phone_number) ?></td>
                <td><?= h($students->specialisation) ?></td>
                <td><?= h($students->first_thesis_subject_completed) ?></td>
                <td><?= h($students->created) ?></td>
                <td><?= h($students->modified) ?></td>
                <td><?= h($students->course_id) ?></td>
                <td><?= h($students->course_level_id) ?></td>
                <td><?= h($students->course_type_id) ?></td>
                <td><?= h($students->thesis_id) ?></td>
                <td><?= h($students->user_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
