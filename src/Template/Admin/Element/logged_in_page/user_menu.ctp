<div class="row">
    <div class="col-12 navbar-container">
        <nav class="navbar navbar-dark bg-dark navbar-expand-md" style="background-color: #e3f2fd;">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown" id="topics_menu_item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= __('Témák kezelése') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" id="thesis_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'index']) ?>"><?= __('Leadott témák') ?></a>
                        <a class="dropdown-item" id="offered_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'OfferedTopics', 'action' => 'index']) ?>"><?= __('Kiírt témák') ?></a>
                        <a class="dropdown-item" id="thesis_topics_statistics" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'statistics']) ?>"><?= __('Elfogadott téma kimutatások') ?></a>
                        <a class="dropdown-item" id="thesis_topics_exports" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'exports']) ?>"><?= __('Elfogadott téma adatok exportálása') ?></a>
                    </div>
                </li>
                <li class="nav-item" id="students_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Students', 'action' => 'index']) ?>"><?= __('Hallgatók kezelése') ?></a>
                </li>
                <li class="nav-item" id="set_topic_filling_in_period_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Information', 'action' => 'setFillingInPeriod']) ?>"><?= __('Témaengedélyező kérdőív engedélyezése') ?></a>
                </li>
                <li class="nav-item" id="set_encryption_requlation_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Information', 'action' => 'setEncryptionRequlation']) ?>"><?= __('Titoktartási kérelem szabályzata') ?></a>
                </li>
                <li class="nav-item dropdown" id="users_menu_item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= __('Felhasználók kezelése') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" id="internal_consultants_index_menu_item" href="<?= $this->Url->build(['controller' => 'InternalConsultants', 'action' => 'index']) ?>"><?= __('Belső konzulensek') ?></a>
                        <a class="dropdown-item" id="reviewers_index_menu_item" href="<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'index']) ?>"><?= __('Bírálók') ?></a>
                        <a class="dropdown-item" id="user_accounts_index_menu_item" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>"><?= __('Felhasználói fiókok') ?></a>
                    </div>
                </li>
                <li class="nav-item dropdown" id="others_menu_item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= __('Egyéb adatok kezelése') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" id="documents_index_menu_item" href="<?= $this->Url->build(['controller' => 'Documents', 'action' => 'index']) ?>"><?= __('Dokumentumok') ?></a>
                        <a class="dropdown-item" id="departments_index_menu_item" href="<?= $this->Url->build(['controller' => 'Departments', 'action' => 'index']) ?>"><?= __('Tanszékek') ?></a>
                        <a class="dropdown-item" id="course_types_index_menu_item" href="<?= $this->Url->build(['controller' => 'CourseTypes', 'action' => 'index']) ?>"><?= __('Képzéstípusok') ?></a>
                        <a class="dropdown-item" id="course_levels_index_menu_item" href="<?= $this->Url->build(['controller' => 'CourseLevels', 'action' => 'index']) ?>"><?= __('Képzésszintek') ?></a>
                        <a class="dropdown-item" id="years_index_menu_item" href="<?= $this->Url->build(['controller' => 'Years', 'action' => 'index']) ?>"><?= __('Tanévek') ?></a>
                        <a class="dropdown-item" id="languages_index_menu_item" href="<?= $this->Url->build(['controller' => 'Languages', 'action' => 'index']) ?>"><?= __('Nyelvek') ?></a>
                    </div>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      </nav>
    </div>
</div>
                


