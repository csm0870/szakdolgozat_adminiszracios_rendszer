<div class="container reviewer-thesisTopics-details">
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
                            <strong><?= __('Állapot') . ': ' ?></strong>
                            <?php
                                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->has('review')){
                                    if($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){
                                        if($thesisTopic->review->confidentiality_contract_status == null) echo __('A titoktartási szerződés feltöltésére vár.');
                                        elseif($thesisTopic->review->confidentiality_contract_status == 1) echo __('A titoktartási szerződés feltölve, véglegesítésre vár.');
                                        elseif($thesisTopic->review->confidentiality_contract_status == 2) echo __('A titoktartási szerződés véglegesítve, tanszékvezető ellenőrzésére vár.');
                                        elseif($thesisTopic->review->confidentiality_contract_status == 3) echo __('A titoktartási szerződés elutasítva, újra feltölthető.');
                                    }else{
                                        if($thesisTopic->review->review_status == null) echo __('A dolgozat bírálatra vár.');
                                        elseif($thesisTopic->review->review_status == 1) echo __('A bírálat véglegesítésre vár.');
                                        elseif($thesisTopic->review->review_status == 2) echo __('A bírálat véglegesítve, bírálati lap feltöltésére vár.');
                                        elseif($thesisTopic->review->review_status == 3) echo __('A bírálati lap feltöltve, véglegesítésre vár.');
                                        elseif($thesisTopic->review->review_status == 4) echo __('A bírálati lap feltöltés véglegesítve. A bírálat a tanszékvezető ellenőrzésére vár.');
                                        elseif($thesisTopic->review->review_status == 5) echo __('A bírálat elutasítva, ismét bírálható.');
                                    }
                                }
                            ?>
                            <?php if($thesisTopic->has('review')){ ?>
                                <?php if((in_array($thesisTopic->review->confidentiality_contract_status, [1, 2]) && $thesisTopic->review->cause_of_rejecting_confidentiality_contract !== null)){ ?>
                                    <br/>
                                    <strong><?= __('Előző feltöltés elutasításának oka') . ': ' ?></strong><?= h($thesisTopic->review->cause_of_rejecting_confidentiality_contract) ?>
                                <?php } ?>
                                <?php if($thesisTopic->review->confidentiality_contract_status == 3){ ?>
                                    <br/>
                                    <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->review->cause_of_rejecting_confidentiality_contract) ?>
                                <?php } ?>
                            <?php } ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Cím') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Leírás') . ':<br/>' ?></strong><?= $thesisTopic->description ?>
                        </p>
                        <p class="mb-1">
                            <strong><?= __('Nyelv') . ': ' ?></strong><?= $thesisTopic->has('language') ? h($thesisTopic->language->name) : '' ?>
                        </p>
                        <p>
                            <strong><?= __('Titkos') . ': ' ?></strong><?= $thesisTopic->confidential === true ? __('Igen') : __('Nem') ?>
                        </p>
                    </fieldset>
                </div>
                <div class="col-12">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#supplementCollapse" aria-expanded="true" aria-controls="collapseOne">
                                        <?= ($thesisTopic->is_thesis === true ? __('Szakdolgozat') : __('Diplomamunka')) . '&nbsp;' .  __('mellékletek') ?>
                                        <i class="fas fa-angle-down fa-lg" id="supplement_arrow_down"></i>
                                        <i class="fas fa-angle-up fa-lg d-none" id="supplement_arrow_up"></i>
                                    </button>
                                </h5>
                            </div>

                            <div id="supplementCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <?php if(($thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 4) ||
                                              $thesisTopic->confidential === false){ ?>
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
                                    <?php }else{ ?>
                                        <p>
                                            <?= __('Mellékletek nem elérhetőek. Titkos dolgozat esetén először a titoktartási nyilatkozatot fel kell tölteni, amelyet a tanszékvezetőnek el kell fogadnia.') ?>
                                        </p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-1">
                    <fieldset class="border-1-grey p-3 text-center">
                        <legend class="w-auto"><?= __('Műveletek') ?></legend>
                        <?php
                            if($thesisTopic->has('review')){
                                if(($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status === 4) ||
                                    $thesisTopic->confidential === false){
                                    echo $this->Html->link(__('Dolgozat bírálata'), ['controller' => 'Reviews', 'action' => 'review', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px mb-2']). '<br/>';
                                } 

                                if($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status == 1){
                                    echo $this->Form->button(__('Titoktartási szerződés feltöltésének véglegesítése'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-info finalizeBtn border-radius-45px mb-2']) . '<br/>';
                                }

                                if($thesisTopic->confidential === true && !in_array($thesisTopic->review->confidentiality_contract_status, [2, 4])){
                                    echo $this->Html->link(__('Titoktartási nyilatkozat feltöltése'), '#', ['class' => 'btn btn-secondary border-radius-45px uploadConfidentialityContractBtn mb-2']). '<br/>';
                                }

                            if($thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status != 4){
                                    echo $this->Html->link(__('Titoktartási nyilatkozat letöltése'), ['controller' => 'Reviews', 'action' => 'confidentialityContractDoc', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px mb-2', 'target' => '_blank']). '<br/>';
                                }
                            }
                        ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if($thesisTopic->confidential === true && $thesisTopic->has('review') && !in_array($thesisTopic->review->confidentiality_contract_status, [2, 4])){ ?>
    <!-- Titoktartási nyilatkozat feltöltése modal -->
    <div class="modal fade" id="uploadConfidentialityContractModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="upload_confidentiality_contract_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        <?php if($thesisTopic->confidential === true && $thesisTopic->has('review') && !in_array($thesisTopic->review->confidentiality_contract_status, [2, 4])){ ?>
            //Tartalom lekeérése a "titoktartási szerződés feltöltése" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'uploadConfidentialityContract', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#upload_confidentiality_contract_container').html(response.content);
            });

            $('.reviewer-thesisTopics-details .uploadConfidentialityContractBtn').on('click', function(e){
                e.preventDefault();
                $('#uploadConfidentialityContractModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->confidential === true && $thesisTopic->has('review') && $thesisTopic->review->confidentiality_contract_status == 1){ ?>
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('.finalizeBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Titoktartási szerződés elküldése ellenőrzésre. Miután elfogadták, a mellékletek elérhetőek lesznek, és lehetősége lesz a bírálatra.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'finalizeConfidentialityContractUpload', $thesisTopic->id], true) ?>';
                });
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