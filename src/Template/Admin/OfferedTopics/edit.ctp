<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'OfferedTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma módosítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->setTemplates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                           'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                
                echo $this->Form->create($offeredTopic, ['id' => 'offeredTopicEditForm']);
                echo $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]);
                echo $this->Form->control('description', ['class' => 'form-control tinymce-input', 'label' => ['text' => __('Leírás')],
                                                          'templates' => ['inputContainer' => '<div class="form-group tinymce-container">{{content}}</div>']]);
                echo $this->Form->control('language_id', ['class' => 'form-control', 'options' => $languages, 'label' => ['text' => __('Nyelv')]]);
                echo $this->Form->control('confidential', ['label' => ['text' => __('Titkos')], 'templates' => ['nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>&nbsp;{{input}}']]);
                echo $this->Form->control('is_thesis', ['class' => 'form-control', 'type' => 'select', 'value' => 0, 'empty' => false, 'options' => [__('Diplomamunka'), __('Szakdolgozat')] ,'label' => ['text' => __('Típus')], 'value' =>  $offeredTopic->is_thesis === true ? 1 : 0]);
                echo $this->Form->control('has_external_consultant', ['class' => 'form-control', 'id' => 'has_external_consultant_select', 'options' => [__('Nincs'), __('Van')] ,'label' => ['text' => __('Van-e külső konzulens jelölt') . ' (' . __('ha van, akkor azon már a hallgató nem változtathat') . ')']]);
                echo $this->Form->control('external_consultant_name', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens neve')],
                                                                       'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                       'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->control('external_consultant_workplace', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens munkahelye')],
                                                                            'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                            'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->control('external_consultant_position', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens poziciója')],
                                                                           'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                           'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->control('external_consultant_email', ['type' => 'email', 'class' => 'form-control', 'label' => ['text' => __('Külső konzulens email címe')],
                                                                        'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                        'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->control('external_consultant_phone_number', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens telefonszáma')], 'placeholder' => __('+36701234567 formátumban.'),
                                                                               'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                               'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->control('external_consultant_address', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens címe')],
                                                                          'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                          'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]);
                echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#offered_topics_index_menu_item').addClass('active');
        
        /**
         * Beállítja a külső konzulensi mezőket a select lista kiválasztott eleme alapján
         * @return {undefined}
         */
        function setExternalConsultantFields(){
            var has_e_consultant = $('#has_external_consultant_select').val();
            if(has_e_consultant == 1){
                $('.external-consultants-data-field').css('display', 'block').find('input').each(function(){
                    this.required = true;
                });
            }else if(has_e_consultant == 0){
                $('.external-consultants-data-field').css('display', 'none').find('input').each(function(){
                    this.required = false;
                });
            }
        }
        setExternalConsultantFields();
        
        $('#has_external_consultant_select').on('change', setExternalConsultantFields);
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#offeredTopicEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#offeredTopicEditForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Téma adatok mentése. Mentés után még módosíthatóak.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#offeredTopicEditForm').trigger('submit');
            });
        });
        
        tinymce.remove();
        tinymce.init({ selector:'.tinymce-input',
                       language : 'hu_HU',
                       forced_root_block : false,
                       entity_encoding : 'raw',
                       branding: false,
                       menubar: false,
                       setup: function (editor){
                                //Tinymce-be gépeléskor beírjuk a textarea-ba azonnal a szöveget
                                editor.on('change', function(e){
                                    editor.save();
                                });
                              },
                       plugins: [
                                    "autoresize advlist lists link textcolor colorpicker",
                                    "insertdatetime media table contextmenu paste wordcount"
                                ],
                       toolbar : ['undo redo | fontsizeselect | fontselect | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify',
                                  'bullist numlist | link unlink | forecolor backcolor | table']});
    });
</script>
