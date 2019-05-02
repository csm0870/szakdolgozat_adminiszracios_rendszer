<div class="form-modal admin-checkConfidentialityContract">
    <?= $ok ? $this->Form->create(null, ['id' => 'checkConfidentialityContractForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Titoktartási szerződés elfogadása') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php }else{ ?>
            <table>
                <?php if(!empty($thesisTopic->review->confidentiality_contract)){ ?>
                    <tr>
                        <td style="padding-bottom: 15px">
                            <?= __('Feltöltött titoktartási szerződés') . ': ' . $this->Html->link($thesisTopic->review->confidentiality_contract, ['action' => 'getUploadedConfidentialityContract', $thesisTopic->id], ['target' => '__blank']) ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>
                        <?= $this->Form->control('accepted', ['label' => __('Titoktartási szerződés elfogadása') . ": ", 'id' => 'accept_confidentiality_contract_select', 'error' => false,
                                                              'options' => [__('Elutasítás'), __('Elfogadás')], 'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                    </td>
                </tr>
                <tr class="rejected_confidentiality_contract_row">
                    <td>
                        <label>
                            <?= __('Az elutasítás oka') . ":" ?>
                        </label>
                    </td>
                </tr>
                <tr class="rejected_confidentiality_contract_row">
                    <td>
                        <?= $this->Form->control('cause_of_rejecting_confidentiality_contract', ['label' => false, 'style' => 'width: 100%', 'error' => false, 'type' => 'textarea',
                                                                                                 'id' => 'cause_of_rejecting_confidentiality_contract_input']) ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? '' : $this->Form->end() ?>
    <div class="overlay overlay-check_confidentiality_contract" style="display:none">
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
             *  accept_thesis_supplements_select select változtatásakor megjelenítjük vagy elrejtjük az okhoz tartozó mezőket
             */
            $('#accept_confidentiality_contract_select').on('change', function(){
               setFields();
            });
            
            function setFields(){
                if($('#accept_confidentiality_contract_select').val() == 1){
                   $('.rejected_confidentiality_contract_row').css('display', 'none');
                   //Textarea resetelése
                   $('#cause_of_rejecting_confidentiality_contract_input').val('');
                   $('#cause_of_rejecting_confidentiality_contract_input')[0].required = false;
               }else if($('#accept_confidentiality_contract_select').val() == 0){
                   $('.rejected_confidentiality_contract_row').css('display', 'table-row');
                   $('#cause_of_rejecting_confidentiality_contract_input')[0].required = true;
               }
            }
            
            setFields();
            
            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#checkConfidentialityContractForm .submitBtn').on('click', function(e){
                e.preventDefault();
                
                //Formvalidáció manuális meghívása
                if($('#checkConfidentialityContractForm')[0].reportValidity() === false) return;

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Titoktartási szerződés elfogadása.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('tanszékvezető') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#checkConfidentialityContractForm').trigger('submit');
                });
            });

            //checkConfidentialityContractForm ajaxform
            $('#checkConfidentialityContractForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-check_confidentiality_contract').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-check_confidentiality_contract').hide();
                        $('#check_confidentiality_contract_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-check_confidentiality_contract').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
