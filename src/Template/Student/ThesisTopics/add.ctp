<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Témaengedélyező kitöltése') ?></h4>
        </div>
        <?php if($can_fill_in_topic === true){ ?>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php 
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            ?>
            <?= $this->Form->create($thesisTopic, ['id' => 'thesisTopicAddForm']) ?>
            <?= $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]) ?>
            <?= $this->Form->control('description', ['class' => 'form-control', 'label' => ['text' => __('Leírás') . ' (' . __('feladatok részletezése') . ')'], 'placeholder' => __('A leírással együtt az adatlap férjen rá egyetlen oldalra!')]) ?>
            <?= $this->Form->control('starting_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Kezdési tanév')]]) ?>
            <?= $this->Form->control('starting_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Kezdési félév')]]) ?>
            <?= $this->Form->control('expected_ending_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Várható leadási tanév')]]) ?>
            <?= $this->Form->control('expected_ending_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Várható leadási félév')]]) ?>
            <?= $this->Form->control('language_id', ['class' => 'form-control', 'options' => $languages, 'label' => ['text' => __('Nyelv')]]) ?>
            <?= $this->Form->control('encrypted', ['hiddenField' => false, 'label' => ['text' => __('Titkos')], 'templates' => ['nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>&nbsp;{{input}}']]) ?>
            <?= $this->Form->control('internal_consultant_id', ['class' => 'form-control', 'label' => ['text' => __('Belső konzulens'), 'options' => $internalConsultants]]) ?>
            <?= $this->Form->control('has_external_consultant', ['id' => 'has_external_consultant', 'class' => 'form-control', 'type' => 'select', 'value' => 1, 'empty' => false, 'options' => [__('Nincs'), __('Van')], 'label' => ['text' => __('Külső konzulens')]]) ?>
            <?= $this->Form->control('external_consultant_name', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens neve')],
                                                                  'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                  'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('external_consultant_workplace', ['class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külő konzulens munkahelye')],
                                                                       'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                       'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('external_consultant_position', ['class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külső konzulens poziciója')],
                                                                      'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                      'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('external_consultant_address', ['class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külső konzulens címe')],
                                                                      'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                      'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('external_consultant_phone_number', ['class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külső konzulens telefonszáma')],
                                                                      'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                      'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('external_consultant_email', ['class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külső konzulens email címe')], 'placeholer' => __('+36701234567 formátumban.'),
                                                                      'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                      'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('cause_of_no_external_consultant', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens kijelölésétől való eltekintés indoklása')],
                                                                         'templates' => ['inputContainer' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}</div>',
                                                                                         'inputContainerError' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('is_thesis', ['class' => 'form-control', 'type' => 'select', 'value' => 0, 'empty' => false, 'options' => [__('Diplomamunka'), __('Szakdolgozat')] ,'label' => ['text' => __('Típus')]]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
        <?php }else{ ?>
            <div class="col-12 text-center">
                <h5 style="color: red"><?= __('Nincs kitöltési időszak!') ?></h5>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        /**
         * Beállítja a külső konzulensi mezőket a select lista kiválasztott eleme alapján
         * @return {undefined}
         */
        function setExternalConsultantFields(){
            var has_e_consultant = $('#has_external_consultant').val();
            if(has_e_consultant == 1){
                $('#cause_of_no_external_consultant').css('display', 'none');
                $('.external-consultants-data-field').css('display', 'block');
            }else if(has_e_consultant == 0){
                $('#cause_of_no_external_consultant').css('display', 'block');
                $('.external-consultants-data-field').css('display', 'none');
            }
        }
        setExternalConsultantFields();
        
        $('#has_external_consultant').on('change', setExternalConsultantFields);
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#thesisTopicAddForm .submitBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Téma adatok mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#thesisTopicAddForm').trigger('submit');
            });
        });
    });
</script>