<div class="home">
    <div class="container">
        <div class="row">
            <?= $this->Flash->render() ?>
        </div>
        <div class="row login-types text-center">
            <?php if(!$administrators){ ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 6], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Hallgatói belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 2], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Belső konzulensi belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 offset-sm-3 offset-lg-0 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 3], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Tanszékvezetői belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }else{ ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 4], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Témakezelő belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 5], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Szakdolgozatkezelő belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-sm-6 offset-sm-3 offset-lg-0 col-lg-4">
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login', 1], true) ?>">
                        <div class="login-type-container">
                            <div class="login-type">
                                <?= __('Adminisztrátori belépés') ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>