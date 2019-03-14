<div class="form-modal reviewer-uploadCReviewDoc">
    <?= $ok ? $this->Form->create(null, ['id' => 'uploadCReviewDocForm']) : '' ?>
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
                        <?= $this->Form->control('review_doc', ['type' => 'file', 'accept' => '.pdf', 'required' => true,
                                                                'label' => ['text' => __('Titoktartási szerződés feltöltése') . ': ']]) ?>
                    </td>
                </tr>
                <?php if(!empty($thesisTopic->review->review_doc)){ ?>
                    <tr>
                        <td style="padding-top: 15px">
                            <?= __('Jelenlegi fájl') . ': ' . $this->Html->link($thesisTopic->review->review_doc, ['action' => 'getReviewDoc', $thesisTopic->id], ['target' => '__blank']) ?>
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
    <div class="overlay overlay-upload_review_doc" style="display:none">
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
            $('#uploadCReviewDocForm .submitBtn').on('click', function(e){
                e.preventDefault();

                //Formvalidáció manuális meghívása
                if($('#uploadCReviewDocForm')[0].reportValidity() === false) return;

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Bírálati lap feltöltése. Mentés után még megváltoztatható.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#uploadCReviewDocForm').trigger('submit');
                });
            });

            //uploadConfidentalityContractForm ajaxform
            $('#uploadCReviewDocForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-upload_review_doc').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-upload_review_doc').hide();
                        $('#upload_review_doc_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'review', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-upload_review_doc').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
