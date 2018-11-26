<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Internal Consultants'), ['controller' => 'InternalConsultants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Internal Consultant'), ['controller' => 'InternalConsultants', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Students'), ['controller' => 'Students', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Student'), ['controller' => 'Students', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Reviewers'), ['controller' => 'Reviewers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reviewer'), ['controller' => 'Reviewers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($user->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Group') ?></th>
            <td><?= $user->has('group') ? $this->Html->link($user->group->name, ['controller' => 'Groups', 'action' => 'view', $user->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($user->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Reviewers') ?></h4>
        <?php if (!empty($user->reviewers)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Workplace') ?></th>
                <th scope="col"><?= __('Position') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->reviewers as $reviewers): ?>
            <tr>
                <td><?= h($reviewers->id) ?></td>
                <td><?= h($reviewers->name) ?></td>
                <td><?= h($reviewers->workplace) ?></td>
                <td><?= h($reviewers->position) ?></td>
                <td><?= h($reviewers->created) ?></td>
                <td><?= h($reviewers->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Reviewers', 'action' => 'view', $reviewers->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Reviewers', 'action' => 'edit', $reviewers->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Reviewers', 'action' => 'delete', $reviewers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reviewers->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Internal Consultants') ?></h4>
        <?php if (!empty($user->internal_consultants)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Room Number') ?></th>
                <th scope="col"><?= __('Phone Number') ?></th>
                <th scope="col"><?= __('Rank') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Department Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->internal_consultants as $internalConsultants): ?>
            <tr>
                <td><?= h($internalConsultants->id) ?></td>
                <td><?= h($internalConsultants->room_number) ?></td>
                <td><?= h($internalConsultants->phone_number) ?></td>
                <td><?= h($internalConsultants->rank) ?></td>
                <td><?= h($internalConsultants->created) ?></td>
                <td><?= h($internalConsultants->modified) ?></td>
                <td><?= h($internalConsultants->department_id) ?></td>
                <td><?= h($internalConsultants->user_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'InternalConsultants', 'action' => 'view', $internalConsultants->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'InternalConsultants', 'action' => 'edit', $internalConsultants->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'InternalConsultants', 'action' => 'delete', $internalConsultants->id], ['confirm' => __('Are you sure you want to delete # {0}?', $internalConsultants->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Students') ?></h4>
        <?php if (!empty($user->students)): ?>
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
            <?php foreach ($user->students as $students): ?>
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
