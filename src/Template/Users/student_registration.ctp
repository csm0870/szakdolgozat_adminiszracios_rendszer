<div class="container">
    <div class="row">
        <div class="col-12 text-center mt-4">
            <h3><?= __('Hallgatói regisztráció') ?></h3>
        </div>
        <div class="col-12 col-sm-6 offset-sm-3 col-xl-4 offset-xl-4">
            <?= $this->Flash->render() ?>
        </div>
        <div class="d-none col-sm-3 d-sm-block col-xl-4">
            &nbsp;
        </div>

        <div class="d-none col-sm-3 d-sm-block col-xl-4">
            &nbsp;
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <?php
                $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                           'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);

                echo $this->Form->create($user, ['id' => 'registrationForm']);

                echo $this->Form->control('username', ['class' => 'form-control', 'label' => ['text' => __('Felhasználónév')],
                                                       'placeholder' => __('Felhasználónév...')]);
                echo $this->Form->control('email', ['class' => 'form-control', 'label' => ['text' => __('Email')],
                                                    'placeholder' => __('Email...')]);
                echo $this->Form->control('password', ['class' => 'form-control', 'label' => ['text' => __('Jelszó')],
                                                       'placeholder' => __('Jelszó...')]);
                echo $this->Form->control('password_again', ['type' => 'password', 'class' => 'form-control', 'label' => ['text' => __('Jelszó újra')],
                                                             'placeholder' => __('Jelszó újra...')]); ?>
                <div class="text-center">
                    <?= $this->Form->button(__('Regisztráció'), ['type' => 'submit', 'class' => 'submitBtn btn btn-primary border-radius-45px']) ?>
                </div>
                <?= $this->Form->end() ?>
        </div>
        <div class="d-none col-sm-3 d-sm-block col-xl-4">
            &nbsp;
        </div>
    </div>
</div>