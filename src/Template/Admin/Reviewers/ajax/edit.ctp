<div class="form-modal">
    <?= $ok ? $this->Form->create($reviewer, ['id' => 'reviewerEditForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Bíráló hozzáadása') ?>
    </div>
    <div class="form-body">
        <?php if($ok === true){ ?>
            <table>
                <tr>
                    <td class="label text-right">
                        <label>
                            <?= __('Név') . ": " ?>
                        </label>
                    </td>
                    <td>
                        <?= $this->Form->control('name', ['error' => false, 'label' => false, 'style' => 'min-width: 80%']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="label text-right">
                        <label>
                            <?= __('Email') . ": " ?>
                        </label>
                    </td>
                    <td>
                        <?= $this->Form->control('email', ['label' => false, 'error' => false, 'style' => 'min-width: 80%']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="label text-right">
                        <label>
                            <?= __('Munkahely') . ": " ?>
                        </label>
                    </td>
                    <td>
                        <?= $this->Form->control('workplace', ['label' => false, 'error' => false, 'style' => 'min-width: 80%']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="label text-right">
                        <label>
                            <?= __('Pozíció') . ": " ?>
                        </label>
                    </td>
                    <td>
                        <?= $this->Form->control('position', ['label' => false, 'error' => false, 'style' => 'min-width: 80%']) ?>
                    </td>
                </tr>
            </table>
        <?php }else{ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok ? $this->Form->end() : '' ?>
    <div class="overlay overlay-edit" style="display:none">
        <div class="spinner fa-3x">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
    </div>
</div>
<?= $this->Html->script('jquery.form.min.js') ?>
<script>
    $(function(){
        /**
         * Confirmation modal megnyitása submit előtt
         */
        $('#reviewerEditForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#reviewerEditForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Bíráló mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#reviewerEditForm').trigger('submit');
            });
        });

        //consultationOccasionAddForm ajaxform
        $('#reviewerEditForm').ajaxForm({
            replaceTarget: false,
            target: null,
            beforeSubmit: function(arr, $form, options) {
                $('.overlay-edit').show();
            },
            success: function (response, textStatus, jqXHR, $form) {
                    if(response.saved == false){
                        $('.overlay-edit').hide();
                        $('#reviewer-edit').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'index'], true)?>';
                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    $('.overlay-edit').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
            }
        });
    });
</script>