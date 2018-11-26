<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reviewer $reviewer
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Reviewer'), ['action' => 'edit', $reviewer->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Reviewer'), ['action' => 'delete', $reviewer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reviewer->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Reviewers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reviewer'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reviews'), ['controller' => 'Reviews', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Review'), ['controller' => 'Reviews', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="reviewers view large-9 medium-8 columns content">
    <h3><?= h($reviewer->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($reviewer->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Workplace') ?></th>
            <td><?= h($reviewer->workplace) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= h($reviewer->position) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($reviewer->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($reviewer->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($reviewer->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($reviewer->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Group Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($reviewer->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->name) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td><?= h($users->group_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Reviews') ?></h4>
        <?php if (!empty($reviewer->reviews)): ?>
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
            <?php foreach ($reviewer->reviews as $reviews): ?>
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
</div>
