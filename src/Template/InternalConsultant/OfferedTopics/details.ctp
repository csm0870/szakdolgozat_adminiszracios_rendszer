<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'OfferedTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Témafoglalás kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <fieldset class="border-1-grey p-3 mb-3">
                        <legend class="w-auto"><?= __('A téma adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Cím') . ': ' ?></strong><?= h($offeredTopic->title) ?>
                        </p>
                        <div class="mb-1">
                            <strong><?= __('Leírás') . ':' ?></strong><br/>
                            <?= $offeredTopic->description ?>
                        </div>
                        <p class="mb-1">
                            <strong><?= __('Nyelv') . ': ' ?></strong><?= $offeredTopic->has('language') ? h($offeredTopic->language->name) : '' ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Titkos') . ': ' ?></strong><?= $offeredTopic->confidential === true ? __('Igen') : __('Nem') ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Típus') . ': ' ?></strong><?= $offeredTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')  ?>
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
                    <fieldset class="border-1-grey p-3 mb-2">
                        <legend class="w-auto"><?= __('A jelentkezett hallgató adatai') ?></legend>
                        <p class="mb-1">
                            <strong><?= __('Név') . ': ' ?></strong><?= $offeredTopic->has('thesis_topic') ? ($offeredTopic->thesis_topic->has('student') ? h($offeredTopic->thesis_topic->student->name) : '') : ''?>
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
                    <?php if($offeredTopic->has('thesis_topic') && $offeredTopic->thesis_topic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.WaitingForInternalConsultantAcceptingOfThesisTopicBooking')){ ?>
                        <div class="row mt-3 mb-4">
                            <div class="col-12 col-sm-6 text-center">
                                <?php
                                    echo $this->Form->create(null, ['id' => 'acceptBookingForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                    echo $this->Form->button(__('Foglalás elfogadása'), ['type' => 'submit', 'class' => 'btn btn-success btn-accept border-radius-45px']);
                                    echo $this->Form->input('offered_topic_id', ['type' => 'hidden', 'value' => $offeredTopic->id]);
                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 1]);
                                    echo $this->Form->end();
                                ?>
                            </div>
                            <div class="col-12 col-sm-6 text-center">
                                <?php
                                    echo $this->Form->create(null, ['id' => 'rejectBookingForm', 'style' => 'display: inline-block', 'url' => ['action' => 'acceptBooking']]);
                                    echo $this->Form->button(__('Foglalás elutasítása'), ['type' => 'submit', 'class' => 'btn btn-danger btn-reject border-radius-45px']);
                                    echo $this->Form->input('offered_topic_id', ['type' => 'hidden', 'value' => $offeredTopic->id]);
                                    echo $this->Form->input('accepted', ['type' => 'hidden', 'value' => 0]);
                                    echo $this->Form->end();
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#offered_topics_index_menu_item').addClass('active');
        
        //Confirmation modal elfogadás előtt
        $('.btn-accept').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
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
            
            $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
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
    });
</script>
