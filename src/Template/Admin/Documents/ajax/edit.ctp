<div class="form-modal document-edit">
    <?= $ok ? $this->Form->create($document, ['id' => 'documentEditForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Dokumentum módosítása') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php }else{ ?>
            <table>
                <tr>
                    <td style="padding-bottom: 5px">
                        <?= __('Név') . ': ' . h($document->name) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control('file', ['type' => 'file', 'required' => true,
                                                          'label' => ['text' => __('Fájl') . ': ']]) ?>
                    </td>
                </tr>
                <?php if(!empty($document->file)){ ?>
                    <tr>
                        <td style="padding-top: 15px">
                            <?= __('Jelenlegi fájl') . ': ' . $this->Html->link($document->file, ['controller' => 'Documents', 'action' => 'downloadFile', $document->id, 'prefix' => false], ['target' => '__blank']) ?>
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
    <div class="overlay overlay-document_edit" style="display:none">
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
            $('#documentEditForm .submitBtn').on('click', function(e){
                e.preventDefault();

                //Formvalidáció manuális meghívása
                if($('#documentEditForm')[0].reportValidity() === false) return;

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Dokumentum mentése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#documentEditForm').trigger('submit');
                });
            });

            //documentEditForm ajaxform
            $('#documentEditForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-document_edit').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-document_edit').hide();
                        $('#document_edit_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'index'], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-document_edit').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
