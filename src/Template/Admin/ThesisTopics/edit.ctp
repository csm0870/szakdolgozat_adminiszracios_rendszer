<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma módosítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php 
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                /*
                if(empty($thesisTopic->starting_year_id)){
                    //Ezzel lefut a lekérdezés és az első ID-t mentjük, illetve betesszük egy tömbbe az értékeket
                    $starting_year_id = null;
                    $has_current_year = false;
                    foreach($years as $id => $year){
                        //Első kulcs
                        if($starting_year_id === null) $starting_year_id = $id;

                        //Ha az aktuális évvel megegyezik az év, akkor mentjük a kulcsot, és nem vizsgáljuk tovább
                        if(!$has_current_year && $year == date('Y')){
                            $has_current_year = true;
                            $starting_year_id = $id;
                            break;
                        }
                    }
                }else{
                    $starting_year_id = $thesisTopic->starting_year_id;
                }
                
                if(empty($thesisTopic->accepted_ending_year_id)){
                    //Első ID-t mentjük, illetve betesszük egy tömbbe az értékeket
                    $accepted_ending_year_id = null;
                    $has_current_year = false;
                    foreach($years as $id => $year){
                        //Első kulcs
                        if($starting_year_id === null) $accepted_ending_year_id = $id;

                        //Ha az aktuális évvel megegyezik az év, akkor mentjük a kulcsot, és nem vizsgáljuk tovább
                        if(!$has_current_year && $year == date('Y')){
                            $has_current_year = true;
                            $accepted_ending_year_id = $id;
                            break;
                        }
                    }
                }else{
                    $accepted_ending_year_id = $thesisTopic->accepted_ending_year_id;
                }*/
            ?>
            <?= $this->Form->create($thesisTopic, ['id' => 'thesisTopicEditForm']) ?>
            <?= $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]) ?>
            <?= $this->Form->control('description', ['class' => 'form-control tinymce-input', 'label' => ['text' => __('Leírás') . ' (' . __('feladatok részletezése') . ')'], 'placeholder' => __('A leírással együtt az adatlap férjen rá egyetlen oldalra!'),
                                                     'templates' => ['inputContainer' => '<div class="form-group tinymce-container">{{content}}</div>']]) ?>
            <?= $this->Form->control('starting_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Kezdési tanév')]]) ?>
            <?= $this->Form->control('starting_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Kezdési félév')]]) ?>
            <?= $this->Form->control('expected_ending_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Várható leadási tanév')]]) ?>
            <?= $this->Form->control('expected_ending_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Várható leadási félév')]]) ?>
            <?= $this->Form->control('language_id', ['class' => 'form-control', 'options' => $languages, 'label' => ['text' => __('Nyelv')]]) ?>
            <?= $this->Form->control('confidential', ['label' => ['text' => __('Titkos')], 'templates' => ['nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>&nbsp;{{input}}']]) ?>
            <?= $this->Form->control('is_thesis', ['class' => 'form-control', 'type' => 'select', 'empty' => false,
                                                   'options' => [__('Diplomamunka'), __('Szakdolgozat')], 'label' => ['text' => __('Típus')], 'value' => $thesisTopic->is_thesis === true ? 1 : 0]) ?>
            <?= $this->Form->control('internal_consultant_id', ['class' => 'form-control', 'label' => ['text' => __('Belső konzulens'), 'options' => $internalConsultants], 'disabled' => true]) ?>
            <?php
                $value = $thesisTopic->cause_of_no_external_consultant === null ? 1 : 0;
            ?>
            <?= $this->Form->control('has_external_consultant', ['id' => 'has_external_consultant', 'class' => 'form-control', 'type' => 'select', 'value' => $value, 'empty' => false, 'options' => [__('Nincs'), __('Van')], 'label' => ['text' => __('Külső konzulens')]]) ?>
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
            <?= $this->Form->control('external_consultant_email', ['type' => 'email', 'class' => 'form-control external-consultants-data-field', 'label' => ['text' => __('Külső konzulens email címe')], 'placeholer' => __('+36701234567 formátumban.'),
                                                                   'templates' => ['inputContainer' => '<div class="form-group external-consultants-data-field">{{content}}</div>',
                                                                                      'inputContainerError' => '<div class="form-group external-consultants-data-field">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('cause_of_no_external_consultant', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens kijelölésétől való eltekintés indoklása')],
                                                                         'templates' => ['inputContainer' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}</div>',
                                                                                         'inputContainerError' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        /**
         * Beállítja a külső konzulensi mezőket a select lista kiválasztott eleme alapján
         * @return {undefined}
         */
        function setExternalConsultantFields(){
            var has_e_consultant = $('#has_external_consultant').val();
            if(has_e_consultant == 1){
                $('#cause_of_no_external_consultant').css('display', 'none').find('textarea')[0].required = false;
                $('.external-consultants-data-field').css('display', 'block').find('input').each(function(){
                    this.required = true;
                });
            }else if(has_e_consultant == 0){
                $('#cause_of_no_external_consultant').css('display', 'block').find('textarea')[0].required = true;
                $('.external-consultants-data-field').css('display', 'none').find('input').each(function(){
                    this.required = false;
                });
            }
        }
        setExternalConsultantFields();
        
        $('#has_external_consultant').on('change', setExternalConsultantFields);
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#thesisTopicEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#thesisTopicEditForm')[0].reportValidity() === false) return;
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Téma adatok mentése. Mentés után még módosíthatóak.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#thesisTopicEditForm').trigger('submit');
            });
        });
        
        tinymce.remove();
        tinymce.init({ selector:'.tinymce-input',
                       forced_root_block : false,
                       language : 'hu_HU',
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