<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
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
                
                //Ezzel lefut a lekérdezés és az első kulcsot mentjük, illetve betesszük egy tömbbe az értékeket
                $key = null;
                $has_current_year = false;
                foreach($years as $id => $year){
                    //Első kulcs
                    if($key === null) $key = $id;
                    
                    //Ha az aktuális évvel megegyezik az év, akkor mentjük a kulcsot
                    if(!$has_current_year && $year == date('Y')){
                        $has_current_year = true;
                        $key = $id;
                    }
                }
            ?>
            <?= $this->Form->create($thesisTopic) ?>
            <?= $this->Form->control('title', ['class' => 'form-control', 'label' => ['text' => __('Cím')]]) ?>
            <?= $this->Form->control('description', ['class' => 'form-control', 'label' => ['text' => __('Leírás') . ' (' . __('feladatok részletezése') . ')']]) ?>
            <?= $this->Form->control('starting_year_id', ['class' => 'form-control', 'options' => $years, 'value' => $key, 'label' => ['text' => __('Kezdési tanév')]]) ?>
            <?= $this->Form->control('starting_semester', ['class' => 'form-control', 'type' => 'select', 'options' => [__('Első félév'), __('Második félév')], 'label' => ['text' => __('Kezdési félév')]]) ?>
            <?= $this->Form->control('language', ['class' => 'form-control', 'label' => ['text' => __('Nyelv')]]) ?>
            <?= $this->Form->control('encrytped', ['hiddenField' => false, 'label' => ['text' => __('Titkos')], 'templates' => ['nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>&nbsp;{{input}}']]) ?>
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
            <?= $this->Form->control('cause_of_no_external_consultant', ['class' => 'form-control', 'label' => ['text' => __('Külső konzulens kijelölésétől való eltekintés indoklása')],
                                                                         'templates' => ['inputContainer' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}</div>',
                                                                                         'inputContainerError' => '<div id="cause_of_no_external_consultant" class="form-group">{{content}}{{error}}</div>']]) ?>
            <?= $this->Form->control('is_thesis', ['class' => 'form-control', 'type' => 'select', 'value' => 0, 'empty' => false, 'options' => [__('Diplomamunka'), __('Szakdolgozat')] ,'label' => ['text' => __('Típus')]]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary', 'type' => 'submit']) ?>
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
        $('#student_thesis_topics_index_menu_item').addClass('active');
        
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