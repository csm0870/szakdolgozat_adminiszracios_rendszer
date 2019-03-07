<div class="form-modal internalConsultant-setThesisGrade">
    <?= $ok ? $this->Form->create(null, ['id' => 'uploadConfidentalityContractForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Titoktartási szerződés feltöltése') ?>
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
                        <?= $this->Form->control('confidentiality_contract', ['type' => 'file', 'accept' => '.pdf',
                                                                              'label' => ['text' => __('Titoktartási szerződés feltöltése') . ': ']]) ?>
                    </td>
                </tr>
                <?php if(!empty($thesisTopic->review->confidentiality_contract)){ ?>
                    <tr>
                        <td style="padding-top: 15px">
                            <?= __('Jelenlegi fájl') . ': ' . $this->Html->link($thesisTopic->review->confidentiality_contract, ['action' => 'getUploadedConfidentialityContract', $thesisTopic->id], ['target' => '__blank']) ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? '' : $this->Form->end() ?>
    <div class="overlay overlay-upload_confidentality_contract" style="display:none">
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
            $('#uploadConfidentalityContractForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Titoktartási szerződés feltöltése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#uploadConfidentalityContractForm').trigger('submit');
                });
            });

            //uploadConfidentalityContractForm ajaxform
            $('#uploadConfidentalityContractForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-upload_confidentality_contract').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-upload_confidentality_contract').hide();
                        $('#upload_confidentiality_contract_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-upload_confidentality_contract').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
