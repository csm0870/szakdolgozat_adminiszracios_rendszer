<div class="form-modal">
    <?= $ok === true ? $this->Form->create($thesisTopic, ['id' => 'applyAcceptedThesisDataForm']) : '' ?>
    <div class="form-header text-center">
         <?= __('Dolgozat adatainak felvitele a Neptun rendszerbe') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php }else{ ?>
            <?php if($thesisTopic->accepted_thesis_data_applyed_to_neptun !== true){ ?>
                <p class="mb-1">
                    <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                </p>
                <?php if($thesisTopic->has('internal_consultant')){ ?>
                    <p class="mb-1">
                        <strong><?= __('Belső konzulens neve') . ': ' ?></strong><?= h($thesisTopic->internal_consultant->name) ?>
                    </p>
                <?php } ?>
                <?php if($thesisTopic->cause_of_no_external_consultant === null){ ?> <!-- Van külső konzulens -->
                    <p class="mb-1">
                        <strong><?= __('Külső konzulens neve') . ': ' ?></strong><?= h($thesisTopic->external_consultant_name) ?>
                    </p>
                <?php }else{ ?>
                    <p class="mb-1">
                        <strong><?= __('Nincs külső konzulens') ?></strong>
                    </p>
                <?php } ?>
                <?php if($thesisTopic->has('review') && $thesisTopic->review->has('reviewer')){ ?>
                    <p class="mb-1">
                        <strong><?= __('Bíráló neve') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->name) ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Bíráló munkahelye') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->workplace) ?>
                    </p>
                <?php } ?>
                <?php if($thesisTopic->has('review')){
                        //Pontok és jegyek kiszámítása
                        $grade = 1;
                        $total_points = (empty($thesisTopic->review->structure_and_style_point) ? 0 : $thesisTopic->review->structure_and_style_point) +
                                        (empty($thesisTopic->review->processing_literature_point) ? 0 : $thesisTopic->review->processing_literature_point) +
                                        (empty($thesisTopic->review->writing_up_the_topic_point) ? 0 : $thesisTopic->review->writing_up_the_topic_point) +
                                        (empty($thesisTopic->review->practical_applicability_point) ? 0 : $thesisTopic->review->practical_applicability_point);

                        if(!empty($thesisTopic->review->structure_and_style_point) && !empty($thesisTopic->review->processing_literature_point) &&
                           !empty($thesisTopic->review->writing_up_the_topic_point) && !empty($thesisTopic->review->practical_applicability_point)){

                            if($total_points >= 45) $grade = 5;
                            else if($total_points < 45 && $total_points >= 38) $grade = 4;
                            else if($total_points < 38 && $total_points >= 31) $grade = 3;
                            else if($total_points < 31 && $total_points >= 26) $grade = 2;
                        }
                ?>
                    <p class="mb-1">
                        <strong><?= __('Bíráló értékelése') . ': ' ?></strong><?= $grade ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Bírálati lap') . ': ' ?></strong><?= $this->Html->link(__('letöltés') . ' ->', ['controller' => 'Reviews', 'action' => 'getReviewDoc', $thesisTopic->id], ['target' => '__blank']) ?>
                    </p>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button($thesisTopic->accepted_thesis_data_applyed_to_neptun !== true ? __('Az adatokat felvittem') : __('Az adatokat nem vitték fel'), ['type' => 'button', 'role' => 'button', 'class' => 'btn ' . ($thesisTopic->accepted_thesis_data_applyed_to_neptun !== true ? 'btn-success' : 'btn-danger') . ' submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? $this->Form->end() : '' ?>
    <div class="overlay overlay-apply_accepted_thesis_data" style="display:none">
        <div class="spinner fa-3x">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
    </div>
</div>
<?php if($ok === true){ ?>
    <?= $this->Html->script('jquery.form.min.js') ?>
    <script>
        $(function(){
            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#applyAcceptedThesisDataForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan felvitted az adatokat?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Igen') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Adatok felvitele a Neptun rendszerbe.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('szakdolgozatkezelő') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#applyAcceptedThesisDataForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#applyAcceptedThesisDataForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-apply_accepted_thesis_data').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-apply_accepted_thesis_data').hide();
                        $('#apply_accepted_thesis_data_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-apply_accepted_thesis_data').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>

