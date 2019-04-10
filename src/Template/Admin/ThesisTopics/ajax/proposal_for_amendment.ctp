<div class="form-modal admin-proposalForAmendment">
    <?= $ok ? $this->Form->create(null, ['id' => 'proposalForAmendmentForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Módosítási javaslat') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg; ?>
            </p>
        <?php }else{ ?>
            <table>
                <tr>
                    <td>
                        <label>
                            <?= __('Javaslat') . ": " ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control('proposal_for_amendment', ['label' => false, 'style' => 'width: 100%', 'required' => true, 'type' => 'textarea']) ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? $this->Form->end() : '' ?>
    <div class="overlay overlay-proposal_for_amendment" style="display:none">
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
            $('#proposalForAmendmentForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').html('<?= __('Módosítási javaslat mentése.') . '<br/><br/>' .__('Művelet végrehajtója') . ': ' . __('tanszékvezető') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#proposalForAmendmentForm').trigger('submit');
                });
            });

            //proposalForAmendmentForm ajaxform
            $('#proposalForAmendmentForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-proposal_for_amendment').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-proposal_for_amendment').hide();
                        $('#proposal_for_amendment_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-proposal_for_amendment').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
