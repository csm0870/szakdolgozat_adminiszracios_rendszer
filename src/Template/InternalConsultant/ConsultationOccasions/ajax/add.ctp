<div class="form-modal internalConsultant-consultationOccasions-add">
    <?= $this->Form->create($consultationOccasion, ['id' => 'consultationOccasionAddForm']) ?>
    <div class="form-header text-center">
        <?= __('Alkalom hozzáadása') ?>
    </div>
    <div class="form-body">
        <table>
            <tr>
                <td class="label text-right">
                    <label>
                        <?= __('Dátum') . ": " ?>
                    </label>
                </td>
                <td style="position: relative">
                    <?= $this->Form->control('date', ['id' => 'date_edit', 'error' => false, 'label' => false, 'autocomplete' => 'off', 'aria-describedby' => "date-1", 'class' => 'datepicker', 'type' => 'text', 'templates' => ['input' => '<div class="input-group-prepend"><span class="input-group-text" id="date-1"><i class="fa fa-calendar"></i></span><input type="{{type}}" name="{{name}}"{{attrs}}/></div>']]) ?>
                </td>
            </tr>
            <tr>
                <td class="label text-right">
                    <label>
                        <?= __('Tevékenység') . ": " ?>
                    </label>
                </td>
                <td style="position: relative">
                    <?= $this->Form->control('activity', ['label' => false, 'error' => false, 'style' => 'width: 100%', 'templates' => [
                                                                                                                                                    'inputContainer' => '{{content}}'
                                                                                                                                        ]]) ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="form-footer text-center">
        <?= $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) ?>
    </div>
    <?= $this->Form->end() ?>
    <div class="overlay overlay-add" style="display:none">
        <div class="spinner fa-3x">
            <i class="fas fa-spinner fa-pulse"></i>
        </div>
    </div>
</div>
<?= $this->Html->script('jquery.form.min.js') ?>
<script>
    $(function(){
        $('.datepicker').datepicker({
            language:'hu',
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
        
        /**
         * Confirmation modal megnyitása submit előtt
         */
        $('#consultationOccasionAddForm .submitBtn').on('click', function(e){
            e.preventDefault();
            
            //Formvalidáció manuális meghívása
            if($('#consultationOccasionAddForm')[0].reportValidity() === false) return;
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('Konzultációs alkalom mentése.') ?>');
                                    
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#consultationOccasionAddForm').trigger('submit');
            });
        });
        
        //consultationOccasionAddForm ajaxform
        $('#consultationOccasionAddForm').ajaxForm({
            replaceTarget: false,
            target: null,
            beforeSubmit: function(arr, $form, options) {
                $('.overlay-add').show();
            },
            success: function (response, textStatus, jqXHR, $form) {
                    if(response.saved == false){
                        $('.overlay-add').hide();
                        $('#consultationOccasion-add').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'index', $consultation->id], true)?>';
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