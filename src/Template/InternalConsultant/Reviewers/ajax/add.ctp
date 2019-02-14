<div class="form-modal">
    <?= $this->Form->create($reviewer, ['id' => 'reviewerAddForm']) ?>
    <div class="form-header text-center">
        <?= __('Bíráló hozzáadása') ?>
    </div>
    <div class="form-body">
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
    </div>
    <div class="form-footer text-center">
        <?= $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) ?>
    </div>
    <?=  $this->Form->end() ?>
    <div class="overlay overlay-add" style="display:none">
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
        $('#reviewerAddForm .submitBtn').on('click', function(e){
            e.preventDefault();

            //Formvalidáció manuális meghívása
            if($('#reviewerAddForm')[0].reportValidity() === false) return;

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Bíráló mentése.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#reviewerAddForm').trigger('submit');
            });
        });

        //consultationOccasionAddForm ajaxform
        $('#reviewerAddForm').ajaxForm({
            replaceTarget: false,
            target: null,
            beforeSubmit: function(arr, $form, options) {
                $('.overlay-add').show();
            },
            success: function (response, textStatus, jqXHR, $form) {
                    if(response.saved == false){
                        $('.overlay-add').hide();
                        $('#reviewer-add').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'index'], true)?>';
                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    $('.overlay-add').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
            }
        });
    });
</script>