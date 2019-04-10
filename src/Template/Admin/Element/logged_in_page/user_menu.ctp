<div class="row">
    <div class="col-12 navbar-container">
        <nav class="navbar navbar-dark bg-dark navbar-expand-md" style="background-color: #e3f2fd;">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" id="dashboard_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'dashboard'], true) ?>">
                        <?= __('Kezdőlap') ?>
                    </a>
                </li>
                <li class="nav-item dropdown" id="topics_menu_item">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?= __('Témák kezelése') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" id="thesis_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'index']) ?>"><?= __('Témaengedélyezők listája') ?></a>
                        <a class="dropdown-item" id="offered_topics_index_menu_item" href="<?= $this->Url->build(['controller' => 'OfferedTopics', 'action' => 'index']) ?>"><?= __('Kiírt témák listája') ?></a>
                    </div>
                </li>
                <li class="nav-item" id="reviewers_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'index']) ?>"><?= __('Bírálók') ?></a>
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
                


