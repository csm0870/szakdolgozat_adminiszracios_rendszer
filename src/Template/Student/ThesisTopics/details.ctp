<div class="container student-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
             <fieldset class="border-1-grey p-3 mb-3">
                <legend class="w-auto"><?= __('A téma adatai') ?></legend>
                <p class="mb-2">
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
            <?php if(in_array($thesisTopic->thesis_topic_status_id, [16, 17, 18, 19, 20, 21, 22, 23, 24, 25])){ ?>
                <fieldset class="border-1-grey p-3 mb-3">
                    <legend class="w-auto"><?= __('Dolgozat értékelése') ?></legend>
                    <p class="mb-2">
                        <strong><?= __('Belső konzulens értékelése') . ': ' ?></strong><?= $thesisTopic->internal_consultant_grade === null ? __('még nincs értékelve') : h($thesisTopic->internal_consultant_grade) ?>
                    </p>
                    <?php if(in_array($thesisTopic->thesis_topic_status_id, [24, 25]) && $thesisTopic->has('review'))
                                echo $this->Html->link(__('Bírálat megtekintése') . ' ->', ['controller' => 'Reviews', 'action' => 'checkReview', $thesisTopic->id], ['class' => 'mb-2', 'style' => 'display: inline-block']); ?>
                </fieldset>
            <?php } ?>
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
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [18, 19, 20, 21, 22, 23, 24, 25])){ ?>
            <div class="col-12">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#supplementCollapse" aria-expanded="true" aria-controls="collapseOne">
                                    <?= ($thesisTopic->is_thesis === null ? __('Szakdolgozat') : ($thesisTopic->is_thesis === true) ? __('Szakdolgozat') : __('Diplomamunka')) . '&nbsp;' .  __('mellékletek') ?>
                                    <i class="fas fa-angle-down fa-lg" id="supplement_arrow_down"></i>
                                    <i class="fas fa-angle-up fa-lg d-none" id="supplement_arrow_up"></i>
                                </button>
                            </h5>
                        </div>

                        <div id="supplementCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                 <ul>
                                    <?php
                                        foreach($thesisTopic->thesis_supplements as $supplement){
                                            if(!empty($supplement->file)){
                                                echo '<li>' .
                                                        $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id], ['target' => '_blank']) .
                                                     '</li>';
                                            }
                                        }
                                    ?>
                                </ul>
                                <div>
                                    <?= $this->Html->link(__('Mellékletek letöltése ZIP-ben'), ['controller' => 'ThesisSupplements', 'action' => 'downloadSupplementInZip', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px' ,'target' => '_blank']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-12 mt-1">
            <fieldset class="border-1-grey p-3 text-center">
                <legend class="w-auto"><?= __('Műveletek') ?></legend>
                <?php
                    if(in_array($thesisTopic->thesis_topic_status_id, [1, 4])){
                        //Ha kitöltési időszak van, csak akkor lehet véglegesíteni
                        if(!empty($can_fill_in_topic) && $can_fill_in_topic === true){
                            echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary border-radius-45px mb-2']) . '<br/>';
                            echo $this->Html->link(__('Téma véglegesítése'), '#', ['class' => 'btn btn-success finalize-thesis-topic-btn border-radius-45px mb-2', 'data-id' => $thesisTopic->id]) . '<br/>';
                        }else{
                            echo $this->Html->link(__('Módosítás'), ['controller' => 'ThesisTopics', 'action' => 'edit', $thesisTopic->id], ['class' => 'btn btn-primary border-radius-45px mb-2']) . '<br/>';
                        }

                        if($thesisTopic->thesis_topic_status_id == 4){
                            echo $this->Html->link(__('Foglalás visszavonása'), '#', ['class' => 'btn btn-danger cancel-booking-btn border-radius-45px mb-2', 'data-id' => $thesisTopic->id]);
                            echo "<br/>";
                        }
                    }
                    
                    if(in_array($thesisTopic->thesis_topic_status_id, [16, 17, 19])) echo $this->Html->link(__('Diplomamunka/szakdolgozat feltöltése'), ['controller' => 'ThesisTopics', 'action' => 'uploadThesis', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px mb-2']) . '<br/>';
                    
                    echo $this->Html->link(__('Témaengedélyező PDF letöltése'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '_blank']) . '<br/>';

                    if($thesisTopic->confidential) echo $this->Html->link(__('Titkosítási kérelem letöltése'), ['controller' => 'ThesisTopics', 'action' => 'encyptionRegulationDoc', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-info border-radius-45px mb-2', 'target' => '_blank']) . '<br/>';
                ?>
            </fieldset>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [18, 19, 20, 21, 22, 23, 24, 25])){ ?>
            /**
             * Accordion megjelenítésekor nyíl cseréje
             */
            $('#supplementCollapse').on('show.bs.collapse', function () {
                $('#supplement_arrow_up').removeClass('d-none');
                $('#supplement_arrow_down').addClass('d-none');
            });

            /**
             * Accordion eltüntetésekor nyíl cseréje
             */
            $('#supplementCollapse').on('hide.bs.collapse', function () {
                $('#supplement_arrow_down').removeClass('d-none');
                $('#supplement_arrow_up').addClass('d-none');
            });
        <?php } ?>
        
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [1, 4]) && !empty($can_fill_in_topic) && $can_fill_in_topic === true){ ?>
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.finalize-thesis-topic-btn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Téma véglegesítése. Véglegesítés után a téma adatai nem módosíthatóak.<br/>A véglegesítés után a hallgatói adatok sem módosíthatóak, csak ha elutasíják a témát.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'finalizeThesisTopic', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
        
        <?php if($thesisTopic->thesis_topic_status_id == 4){ ?>
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.cancel-booking-btn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan visszautasítod a foglalást?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', 'red');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Foglalás visszautasítása.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'cancelBooking', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
    });
</script>
