<div class="row">
    <div class="col-12 navbar-container">
        <nav class="navbar navbar-dark bg-dark navbar-expand-md" style="background-color: #e3f2fd;">
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" id="dashboard_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'dashboard'], true) ?>"><?= __('Dashboard') ?></a>
                </li>
                <!-- Témakezelő menüpontok-->
                <?php if($logged_in_user->group_id == 4){ ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= __('Leadott témák') ?></a>
                    </li>
                    <li class="nav-item" id="set_topic_filling_in_period_menu_item">
                        <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Information', 'action' => 'setFillingInPeriod']) ?>"><?= __('Témaengedélyező kérdőív engedélyezése') ?></a>
                    </li>
                    <li class="nav-item" id="set_encryption_requlation_menu_item">
                        <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Information', 'action' => 'setEncryptionRequlation']) ?>"><?= __('Titoktartási kérelem szabályzata') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= __('Kimutatások') ?></a>
                    </li>
                <?php }elseif($logged_in_user->group_id == 6){ ?>
                    <li class="nav-item" id="student_data_menu_item">
                        <a class="nav-link" href="<?= $this->Url->build(['controller' => 'Students', 'action' => 'studentEdit']) ?>"><?= __('Hallgatói adatok') ?></a>
                    </li>
                    <li class="nav-item" id="student_thesis_topics_index_menu_item">
                        <a class="nav-link" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'studentIndex']) ?>"><?= __('Témaengedélyező') ?></a>
                    </li>
                <?php }?>
            </ul>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      </nav>
    </div>
</div>
                


