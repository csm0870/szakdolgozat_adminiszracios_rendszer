<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'OfferedTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Kiírt téma') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('A téma adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Állapot') . ': ' ?></strong>
                            <?php
                                if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->has('student')){
                                    echo __('A témára jelentkeztek.') . ' ';
                                    
                                    echo '<br/><strong>' . __('Foglalás állapota') . ': ' . '</strong>';
                                    if(in_array($offeredTopic->thesis_topic->thesis_topic_status_id, [\Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking'),
                                                                                                      \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForStudentFinalizingOfThesisTopicBooking')])){
                                        if($offeredTopic->thesis_topic->has('thesis_topic_status')) echo h($offeredTopic->thesis_topic->thesis_topic_status->name);
                                    }else{
                                        echo __('A témafoglalás lezárult.') . ' ' . $this->Html->link(__('A leadott téma részletei') . '->', ['controller' => 'ThesisTopics', 'action' => 'details', $offeredTopic->thesis_topic->id]);
                                    }
                                }else echo __('Nincs jelentkezett hallgató');
                            ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Téma címe') . ': ' ?></strong><?= h($offeredTopic->title) ?>
                        </p>
                        <div class="mb-1">
                            <strong><?= __('Téma leírása') . ':' ?></strong><br/>
                            <?= $offeredTopic->description ?>
                        </div>
                        <p class="mb-1">
                            <strong><?= __('Nyelv') . ': ' ?></strong><?= $offeredTopic->has('language') ? h($offeredTopic->language->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Titkos') . ': ' ?></strong><?= $offeredTopic->confidential === true ? __('Igen') : __('Nem') ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Téma típusa') . ': ' ?></strong><?= $offeredTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')  ?>
                        </p>
                        <?php if($offeredTopic->has_external_consultant === true){ ?>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens neve') . ': ' ?></strong><?= h($offeredTopic->external_consultant_name) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens munkahelye') . ': ' ?></strong><?= h($offeredTopic->external_consultant_workplace) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens pozíciója') . ': ' ?></strong><?= h($offeredTopic->external_consultant_position) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens email címe') . ': ' ?></strong><?= h($offeredTopic->external_consultant_email) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens telefonszáma') . ': ' ?></strong><?= h($offeredTopic->external_consultant_phone_number) ?>
                            </p>
                            <p class="mb-1">
                                <strong><?= __('Külső konzulens címe') . ': ' ?></strong><?= h($offeredTopic->external_consultant_address) ?>
                            </p>
                        <?php }else{ ?>
                            <p class="mb-1">
                                <strong><?= __('Nincs hozzárendelve külső konzulens') ?></strong>
                            </p>
                        <?php } ?>
                    </fieldset>
                    <?php if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->has('student')){ ?>
                    <fieldset class="border-1-grey p-3 mb-2">
                        <legend class="w-auto"><?= __('A jelentkezett hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? h($offeredTopic->thesis_topic->student->name) : '') : ''?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Neptun kód') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? h($offeredTopic->thesis_topic->student->neptun) : '') : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Szak') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? ($offeredTopic->thesis_topic->student->has('course') ? h($offeredTopic->thesis_topic->student->course->name) : '') : '') : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? ($offeredTopic->thesis_topic->student->has('course_level') ? h($offeredTopic->thesis_topic->student->course_level->name) : '') : '') : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? ($offeredTopic->thesis_topic->student->has('course_type') ? h($offeredTopic->thesis_topic->student->course_type->name) : '') : '') : '' ?>
                        </p>
                    </fieldset>
                    <?php } ?>
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->has('student') &&
                               $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){
                            
                                 echo $this->Form->create(null, ['id' => 'acceptBookingForm', 'class' => 'mb-2', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                    echo $this->Form->button(__('Foglalás elfogadása'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px']);
                                    echo $this->Form->input('offered_topic_id', ['type' => 'hidden', 'value' => $offeredTopic->id]);
                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                    echo $this->Form->end();
                                    
                                    echo $this->Form->create(null, ['id' => 'rejectBookingForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                    echo $this->Form->button(__('Foglalás elutasítása'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px']);
                                    echo $this->Form->input('offered_topic_id', ['type' => 'hidden', 'value' => $offeredTopic->id]);
                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                    echo $this->Form->end();
                                    echo "<br/>";
                            }
                            
                            if(($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')) || !$offeredTopic->has('thesis_topic')){
                                echo $this->Html->link(__('Téma módosítása'), ['action' => 'edit', $offeredTopic->id], ['class' => 'btn btn-primary border-radius-45px mb-2']) . '<br/>';
                                echo $this->Html->link(__('Téma törlése'), '#', ['class' => 'btn btn-danger border-radius-45px delete-btn']);
                                echo $this->Form->postLink('', ['action' => 'delete', $offeredTopic->id], ['style' => 'display: none', 'id' => 'deleteOfferedTopic_' . $offeredTopic->id]);
                            }
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
        $('#offered_topics_index_menu_item').addClass('active');
        
        <?php if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->has('student') &&
                               $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){ ?>
            
            //Confirmation modal elfogadás előtt
            $('.btn-accept').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan elfogadod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Foglalás elfogadása. Elfogadás után a téma adatai már nem módosíthatók.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#acceptBookingForm').trigger('submit');
                });
            });

            //Confirmation modal elutasítás előtt
            $('.btn-reject').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan elutasítod?') ?>');
                $('#confirmationModal .msg').text('<?= __('Foglalás elutasítása. Elutasítás után a téma újra foglalható lesz a hallgatóknak.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#rejectBookingForm').trigger('submit');
                });
            });
        <?php } ?>
        
        <?php if(($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')) || !$offeredTopic->has('thesis_topic')){ ?>
            //Törléskor confirmation modal a megerősítésre
            $('.delete-btn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
                $('#confirmationModal .msg').text('<?= __('Téma törlése.') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());

                $('#confirmationModal').modal('show');

                var id = $(this).data('id');
                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#deleteOfferedTopic_' + '<?= $offeredTopic->id ?>').trigger('click');
                });
            });
        <?php } ?>
    });
</script>
