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
                <li class="nav-item" id="thesis_topics_index_menu_item">
                    <a class="nav-link" href="<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'index']) ?>"><?= __('Dolgozatok kezelése') ?></a>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      </nav>
    </div>
</div>
                


