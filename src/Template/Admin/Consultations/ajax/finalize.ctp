<div class="form-modal internalConsultant-setThesisSubjectCompleted">
    <?= $ok ? $this->Form->create($consultation, ['id' => 'finalizeConsultationForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Konzultációs csoport véglegesítése') ?>
    </div>
    <div class="form-body">
        <?php if($ok){ ?>
        <table>
                <tr>
                    <td>
                        <label>
                            <?= __('A dolgozat megfelelt a formai követelményeknek, így feltölthető, és bírálatra bocsátható') . ": " ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control('accepted', ['label' => false, 'options' => [__('Nem felelt meg'), __('Megfelelt')], 'error' => false]) ?>
                    </td>
                </tr>
            </table>
        <?php }else{ ?>
            <p class="text-center">
                <?= $error_msg; ?>
            </p>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok ? $this->Form->end() : ''?>
    <div class="overlay overlay-finalize_consultation" style="display:none">
        <div class="spinner fa-3x">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
    </div>
</div>
<?php if($ok){ ?>
    <?= $this->Html->script('jquery.form.min.js') ?>
    <script>
        $(function(){
            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#finalizeConsultationForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Konzultációs csoport véglegesítése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#finalizeConsultationForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#finalizeConsultationForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-finalize_consultation').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-finalize_consultation').hide();
                        $('#finalize_consultation_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'index', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-finalize_consultation').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
