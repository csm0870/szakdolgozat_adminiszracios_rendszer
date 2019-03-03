<div class="home">
    <div class="container">
        <div class="row">
            <?= $this->Flash->render() ?>
        </div>
        <div class="row login-types text-center">
            <div class="col-12 col-sm-6 col-md-5 offset-md-1">
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 1], true) ?>">
                    <div class="login-type-container">
                        <div class="login-type">
                            <?= __('Hallgatói belépés') ?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-5">
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 2], true) ?>">
                    <div class="login-type-container">
                        <div class="login-type">
                            <?= __('Oktatói belépés') ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>