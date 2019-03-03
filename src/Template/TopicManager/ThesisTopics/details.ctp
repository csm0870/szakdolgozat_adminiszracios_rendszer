<div class="container headOfDepartment-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-details-body">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('A téma adatai') ?></legend>
                        <p class="mb-4">
                            <strong><?= __('Állapot') . ': ' ?></strong><?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Belső konzulens') . ': ' ?></strong><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                        </p>
                        <div class="mb-1">
                            <strong><?= __('Téma leírása') . ':' ?></strong><br/>
                            <?= $thesisTopic->description ?>
                        </div>
                        <p class="mb-1">
                            <strong><?= __('Nyelv') . ': ' ?></strong><?= $thesisTopic->has('language') ? h($thesisTopic->language->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Titkos') . ': ' ?></strong><?= $thesisTopic->confidential === true ? __('Igen') : __('Nem') ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Kezdési tanév') . ': ' ?></strong><?= $thesisTopic->has('starting_year') ? h($thesisTopic->starting_year->year) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Kezdési félév') . ': ' ?></strong><?= $thesisTopic->starting_semester === null ? '' : ($thesisTopic->starting_semester === true ? __('Tavasz') : __('Ősz') ) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Várható leadási tanév') . ': ' ?></strong><?= $thesisTopic->has('expected_ending_year') ? h($thesisTopic->expected_ending_year->year) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Várható leadási félév') . ': ' ?></strong><?= $thesisTopic->expected_ending_semester === null ? '' : ($thesisTopic->expected_ending_semester === true ? __('Tavasz') : __('Ősz') ) ?>
                        </p>
                        <?php if($thesisTopic->cause_of_no_external_consultant === null){ ?> <!-- Van külső konzulens -->
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens neve') . ': ' ?></strong><?= h($thesisTopic->external_consultant_name) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens munkahelye') . ': ' ?></strong><?= h($thesisTopic->external_consultant_workplace) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens poziciója') . ': ' ?></strong><?= h($thesisTopic->external_consultant_position) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens email címe') . ': ' ?></strong><?= h($thesisTopic->external_consultant_email) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens telefonszáma') . ': ' ?></strong><?= h($thesisTopic->external_consultant_phone_number) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens címe') . ': ' ?></strong><?= h($thesisTopic->external_consultant_address) ?>
                            </p>
                        <?php }else{ ?>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulenstól való eltekintés indoklása') . ': ' ?></strong><?= h($thesisTopic->cause_of_no_external_consultant) ?>
                            </p>
                        <?php } ?>
                    </fieldset>
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('Hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Neptun kód') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Szak') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?>
                        </p>
                    </fieldset>
                </div>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                                                                            
                            //Ha van külső konzulens, akkor elfogadhatja annak aláírását
                            if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->thesis_topic_status_id == 10){
                                echo $this->Form->create(null, ['id' => 'acceptThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                echo $this->Form->button(__('Téma elfogadás'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                echo $this->Form->end();
                                echo "&nbsp;&nbsp;";
                                echo $this->Form->create(null, ['id' => 'rejectThesisTopicForm', 'style' => 'display: inline-block', 'url' => ['action' => 'accept']]);
                                echo $this->Form->button(__('Téma elutasítás'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px mb-2']);
                                echo $this->Form->input('thesis_topic_id', ['type' => 'hidden', 'value' => $thesisTopic->id]);
                                echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                echo $this->Form->end();
                                echo '<br/>';
                            }
                            
                            echo $this->Html->link(__('Témaengedélyező PDF letöltése'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '_blank']) . '<br/>';
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if($thesisTopic->cause_of_no_external_consultant === null && $thesisTopic->thesis_topic_status_id == 10){ ?>
            //Confirmation modal elfogadás előtt
            $('.headOfDepartment-thesisTopics-details .btn-accept').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Téma elfogadása.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#acceptThesisTopicForm').trigger('submit');
                });
            });

            //Confirmation modal elutasítás előtt
            $('.headOfDepartment-thesisTopics-details .btn-reject').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Téma elutasítása.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#rejectThesisTopicForm').trigger('submit');
                });
            });
        <?php } ?>
    });
</script>