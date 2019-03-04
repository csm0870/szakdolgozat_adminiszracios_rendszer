<div class="form-modal headOfDepartment-decideToContinueAfterFailedFirstThesisSubject">
    <?= $no_thesis_topic ? '' : $this->Form->create($thesisTopic, ['id' => 'decideToContinueAfterFailedFirstThesisSubjectForm']) ?>
    <div class="form-header text-center">
        <?= __('Első diplomakurzus sikertelen, döntés a folytatásról') ?>
    </div>
    <div class="form-body">
        <?php if($no_thesis_topic){ ?>
            <p class="text-center">
                <?= $error_msg; ?>
            </p>
        <?php }else{ ?>
            <table>
                <tr>
                    <td>
                        <label>
                            <?= __('Belső konzulens javaslata a folytatásról') . ": " ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control('first_thesis_subject_failed_suggestion', ['label' => false, 'style' => 'width: 100%; resize: none', 'error' => false,
                                                                                            'readonly' => true]) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control('decide_to_continue', ['label' => __('A hallgató javíthatja az első diplomakurzust a jelenlegi témával') . ": ", 'id' => 'first_thesis_subject_completed', 'error' => false,
                                                                                    'options' => [__('Nem, új témát kell választania'), __('Igen')], 'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $no_thesis_topic ? '' : $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) ?>
    </div>
    <?= $no_thesis_topic ? '' : $this->Form->end() ?>
    <div class="overlay overlay-decide_to_continue_after_failed_first_thesis_subject" style="display:none">
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
             * Confirmation modal megnyitása submit előtt
             */
            $('#decideToContinueAfterFailedFirstThesisSubjectForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Első diplomakurzus sikertelen, döntés a folytatásról.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#decideToContinueAfterFailedFirstThesisSubjectForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#decideToContinueAfterFailedFirstThesisSubjectForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-decide_to_continue_after_failed_first_thesis_subject').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-decide_to_continue_after_failed_first_thesis_subject').hide();
                        $('#decide_to_continue_after_failed_first_thesis_subject_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-decide_to_continue_after_failed_first_thesis_subject').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
