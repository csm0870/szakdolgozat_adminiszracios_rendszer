<div class="form-modal internalConsultant-consultationOccasions-edit">
    <?= $ok ? $this->Form->create($consultationOccasion, ['id' => 'consultationOccasionEditForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Alkalom szerkesztése') ?>
    </div>
    <div class="form-body">
        <?php if($ok){ ?>
            <table>
                <tr>
                    <td class="label text-right">
                        <label>
                            <?= __('Dátum') . ": " ?>
                        </label>
                    </td>
                    <td style="position: relative">
                        <?= $this->Form->control('date', ['label' => false, 'error' => false, 'autocomplete' => 'off', 'value' => empty($consultationOccasion->date) ? '' : $this->Time->format($consultationOccasion->date, 'yyyy-MM-dd'), 'aria-describedby' => "date-2", 'class' => 'datepicker', 'type' => 'text', 'templates' => ['input' => '<div class="input-group-prepend"><span class="input-group-text" id="date-2"><i class="fa fa-calendar"></i></span><input type="{{type}}" name="{{name}}"{{attrs}}/></div>']]) ?>
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
<?php if($ok){ ?>
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
            $('#consultationOccasionEditForm .submitBtn').on('click', function(e){
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
                    $('#consultationOccasionEditForm').trigger('submit');
                });
            });

            //consultationOccasionEditForm ajaxform
            $('#consultationOccasionEditForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-edit').show();
                },
                success: function (response, textStatus, jqXHR, $form) {
                        if(response.saved == false){
                            $('.overlay-edit').hide();
                            $('#consultationOccasion-edit').html(response.content);
                            $('#error_modal_ajax .error-msg').html(response.error_ajax);
                            $('#error_modal_ajax').modal('show');
                        }else{
                            location.href = '<?= $this->Url->build(['action' => 'index', $consultation->id], true)?>';
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
<?php } ?>