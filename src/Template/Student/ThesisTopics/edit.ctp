<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témaengedélyező kitöltése') ?></h4>
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
            <?= $this->Form->create($thesisTopic) ?>
            <?= $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]) ?>
            <?= $this->Form->control('description', ['class' => 'form-control', 'label' => ['text' => __('Leírás') . ' (' . __('feladatok részletezése') . ')'], 'placeholder' => __('A leírással együtt az adatlap férjen rá egyetlen oldalra!')]) ?>
            <?= $this->Form->control('starting_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Kezdési tanév')]]) ?>
            <?= $this->Form->control('starting_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Kezdési félév')]]) ?>
            <?= $this->Form->control('expected_ending_year_id', ['class' => 'form-control', 'options' => $years, 'label' => ['text' => __('Várható leadási tanév')]]) ?>
            <?= $this->Form->control('expected_ending_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Ősz'), __('Tavasz')], 'label' => ['text' => __('Várható leadási félév')]]) ?>
            <?= $this->Form->control('language_id', ['class' => 'form-control', 'options' => $languages, 'label' => ['text' => __('Nyelv')]]) ?>
            <?= $this->Form->control('encrypted', ['label' => ['text' => __('Titkos')], 'templates' => ['nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>&nbsp;{{input}}']]) ?>
            <?= $this->Form->control('internal_consultant_id', ['class' => 'form-control', 'label' => ['text' => __('Belső konzulens'), 'options' => $internalConsultants]]) ?>
            <?= $this->Form->control('has_external_consultant', ['id' => 'has_external_consultant', 'class' => 'form-control', 'type' => 'select', 'value' => $thesisTopic->cause_of_no_external_consultant === null ? 1 : 0, 'empty' => false, 'options' => [__('Nincs'), __('Van')], 'label' => ['text' => __('Külső konzulens')]]) ?>
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
            <?= $this->Form->control('is_thesis', ['class' => 'form-control', 'type' => 'select', 'empty' => false, 'options' => [__('Diplomamunka'), __('Szakdolgozat')] ,'label' => ['text' => __('Típus')]]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
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
    });
</script>