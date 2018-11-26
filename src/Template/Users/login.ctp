<div class="login-page">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center group-name">
                <?= $group_name . '&nbsp;' . __('belépés') ?>
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
            <div class="col-12 col-sm-6 col-xl-4 login-box">
                <?php
                    $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                               'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>',]);

                    echo $this->Form->create(null, ['id' => 'loginForm']);

                    echo $this->Form->control('email', ['class' => 'form-control', 'label' => ['text' => __('Email')],
                                                        'required' => true, 'placeholder' => __('Email...')]);
                    echo $this->Form->control('password', ['class' => 'form-control', 'label' => ['text' => __('Jelszó')],
                                                           'required' => true, 'placeholder' => __('Jelszó...')]); ?>
                    <div class="text-center">
                        <?= $this->Form->button(__('Belépés'), ['type' => 'submit', 'class' => 'submitBtn btn btn-outline-primary']) ?>
                    </div>
                    <?= $this->Form->end() ?>
            </div>
            <div class="d-none col-sm-3 d-sm-block col-xl-4">
                &nbsp;
            </div>
        </div>
    </div>
</div>