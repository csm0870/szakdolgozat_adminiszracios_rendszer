<div class="form-modal admin-setReviewerSuggestion">
    <?= $ok ? $this->Form->create(null, ['id' => 'setReviewerSuggestionForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Bíráló személyének kijelölése') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php }else{ ?>
            <table>
                <tr>
                    <td>
                        <?= $this->Form->control('reviewer_id', ['label' => __('Bíráló') . ':', 'options' => $reviewers_list, 'id' => 'reviewer_select', 'error' => false,
                                                                 'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                    </td>
                </tr>
            </table>
            <?php foreach($reviewers as $reviewer){ ?>
                <div class="mt-2 reviewer-details" id="reviewer_details_<?= $reviewer->id?>" style="display: none">
                    <p class="mb-1 mt-2">
                        <strong><?= __('Név') . ': ' ?></strong><?= h($reviewer->name) ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Email') . ': ' ?></strong><?= h($reviewer->email) ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Munkahely') . ': ' ?></strong><?= h($reviewer->workplace) ?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Pozició') . ': ' ?></strong><?= h($reviewer->position) ?>
                    </p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? '' : $this->Form->end() ?>
    <div class="overlay overlay-set_reviewer_suggestion" style="display:none">
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
             * Bíráló adatainak megjelenítése
             * 
             * @param {type} id Bíráló ID-ja
             * @return {undefined}
             */
            function showReviewerDetails(id){
                $('.reviewer-details').css('display', 'none');
                $('#reviewer_details_' + id).css('display', 'block');
            }
            
            showReviewerDetails($('#reviewer_select').val());
            
            /**
             * Bírláó választásakor annak részletei megjelennek
             */
            $('#reviewer_select').on('change', function(){
                var id = $(this).val();
                showReviewerDetails(id);
            });
            
            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#setReviewerSuggestionForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Szakdolgozat/Diplomakurzus bírálójának a kijelölése.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('belső konzulens')?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#setReviewerSuggestionForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#setReviewerSuggestionForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-set_reviewer_suggestion').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-set_reviewer_suggestion').hide();
                        $('#set_first_thesis_subject_completed_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-set_reviewer_suggestion').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
