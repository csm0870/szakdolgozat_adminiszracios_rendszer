<div class="container">
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
                    <?php if($thesisTopic->thesis_topic_status_id == 19){ ?>
                        <p class="mb-3">
                            <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->cause_of_rejecting_thesis_supplements) ?>
                        </p>
                    <?php } ?>
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
        <?php if(in_array($thesisTopic->thesis_topic_status_id, [18, 19, 20])){ ?>
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
                                                        $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id, 'prefix' => false], ['target' => '_blank']) .
                                                     '</li>';
                                            }
                                        }
                                    ?>
                                </ul>
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
                    if($thesisTopic->thesis_topic_status_id == 18) echo $this->Form->button(__('Mellékletek elfogadása'), ['class' => 'btn btn-primary acceptThesisSupplementsBtn border-radius-45px mb-2']) . '<br/>';

                    echo $this->Html->link(__('Témaengedélyező PDF letöltése'), ['controller' => 'ThesisTopics', 'action' => 'exportPdf', $thesisTopic->id, 'prefix' => false], ['class' => 'btn btn-primary border-radius-45px mb-2', 'target' => '_blank']) . '<br/>';
                ?>
            </fieldset>
        </div>
    </div>
</div>
<?php if($thesisTopic->thesis_topic_status_id == 18){ ?>
    <!-- Diplomakurzus első félévének teljesítésének rögzítése modal -->
    <div class="modal fade" id="acceptThesisSupplementsModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="accept_thesis_supplements_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if($thesisTopic->thesis_topic_status_id == 18){ ?>
            //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" modalba
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'acceptThesisSupplements', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#accept_thesis_supplements_container').html(response.content);
            });

            $('.acceptThesisSupplementsBtn').on('click', function(e){
                e.preventDefault();
                $('#acceptThesisSupplementsModal').modal('show');
            });
        <?php } ?>
        
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
    });
</script>
