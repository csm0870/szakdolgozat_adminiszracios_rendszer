<div class="form-modal internalConsultant-setThesisSubjectCompleted">
    <?= $no_thesis_topic ? '' : $this->Form->create($thesisTopic, ['id' => 'setThesisSubjectCompletedForm']) ?>
    <div class="form-header text-center">
        <?= __('Diplomakurzus első félévének teljesítésének rögzítése') ?>
    </div>
    <div class="form-body">
        <?php 
            if($no_thesis_topic){
                echo $error_msg;
            }else{ ?>
                <table>
                    <tr>
                        <td>
                            <?= $this->Form->control('first_thesis_subject_completed', ['label' => __('Első diplomakurzus teljesítve') . ": ", 'id' => 'first_thesis_subject_completed', 'error' => false,
                                                                                        'options' => [__('Nem'), __('Igen')], 'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                        </td>
                    </tr>
                    <tr class="first_thesis_subject_failed_suggestion_row">
                        <td>
                            <label>
                                <?= __('Javaslat a téma javítására vagy új választásához (tanszékvezető döntéséhez)') . ": " ?>
                            </label>
                        </td>
                    </tr>
                    <tr class="first_thesis_subject_failed_suggestion_row">
                        <td>
                            <?= $this->Form->control('first_thesis_subject_failed_suggestion', ['label' => false, 'style' => 'width: 100%', 'error' => false,
                                                                                                'id' => 'first_thesis_subject_failed_suggestion']) ?>
                        </td>
                    </tr>
                </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $no_thesis_topic ? '' : $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) ?>
    </div>
    <?= $no_thesis_topic ? '' : $this->Form->end() ?>
    <div class="overlay overlay-set_first_thesis_subject_completed_container" style="display:none">
        <div class="spinner fa-3x">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
    </div>
</div>
<?php if(!$no_thesis_topic){ ?>
    <?= $this->Html->script('jquery.form.min.js') ?>
    <script>
        $(function(){
            /**
             *  first_thesis_subject_completed select változtatásakor megjelenítjük vagy elrejtjük a javaslathoz tartozó mezőket
             */
            $('#first_thesis_subject_completed').on('change', function(){
               if($(this).val() == 1){
                   $('.first_thesis_subject_failed_suggestion_row').css('display', 'none');
                   //Textarea resetelése
                   $('#first_thesis_subject_failed_suggestion').val('');
               }else if($(this).val() == 0){
                   $('.first_thesis_subject_failed_suggestion_row').css('display', 'table-row');
               }
            });

            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#setThesisSubjectCompletedForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Diplomakurzus első félévének teljesítésének rögzítése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#setThesisSubjectCompletedForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#setThesisSubjectCompletedForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-set_first_thesis_subject_completed_container').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-set_first_thesis_subject_completed_container').hide();
                        $('#set_first_thesis_subject_completed_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-set_first_thesis_subject_completed_container').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
