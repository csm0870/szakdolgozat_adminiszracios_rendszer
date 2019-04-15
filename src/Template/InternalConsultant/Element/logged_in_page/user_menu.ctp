<div class="row">
    <div class="col-12 navbar-container">
        <nav class="navbar navbar-dark bg-dark navbar-expand-md" style="background-color: #e3f2fd;">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" id="notifications_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'index'], true) ?>">
                        <?= ($has_unread_notification === true ? '<sup class="unread-sup" style="padding-right: 2px">' . __('Új') . '</sup>' : '') . __('Értesítések') ?>
                    </a>
                </li>
                <li class="nav-item dropdown" id="topics_menu_item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?= __('Témák kezelése') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" id="thesis_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'index']) ?>"><?= __('Leadott témák') ?></a>
                        <a class="dropdown-item" id="offered_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'OfferedTopics', 'action' => 'index']) ?>"><?= __('Témakiírás') ?></a>
                    </div>
                </li>
                <li class="nav-item" id="reviewers_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'index']) ?>"><?= __('Bírálók kezelése') ?></a>
                </li>
                <li class="nav-item" id="final_exam_subjects_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'FinalExamSubjects', 'action' => 'index']) ?>"><?= __('Záróvizsga-tárgyak') ?></a>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      </nav>
    </div>
</div>
                


