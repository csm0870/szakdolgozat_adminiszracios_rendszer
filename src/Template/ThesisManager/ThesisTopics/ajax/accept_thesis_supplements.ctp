<div class="form-modal">
    <?= $ok === true ? $this->Form->create($thesisTopic, ['id' => 'acceptThesisSupplements']) : '' ?>
    <div class="form-header text-center">
        <?= __('Szakdolgozat/Diplomamunka mellékleteinek elfogadása') ?>
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
                        <?= $this->Form->control('accepted', ['label' => __('Mellékletek elfogadása') . ": ", 'id' => 'accept_thesis_supplements_select', 'error' => false,
                                                              'options' => [__('Elutasítás'), __('Elfogadás')], 'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                    </td>
                </tr>
                <tr class="rejected_supplements_row">
                    <td>
                        <label>
                            <?= __('Az elutasítás oka') . ": " ?>
                        </label>
                    </td>
                </tr>
                <tr class="rejected_supplements_row">
                    <td>
                        <?= $this->Form->control('cause_of_rejecting_thesis_supplements', ['label' => false, 'style' => 'width: 100%', 'error' => false,
                                                                                            'id' => 'cause_of_rejecting_thesis_supplements_input']) ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? $this->Form->end() : '' ?>
    <div class="overlay overlay-accept_thesis_supplements" style="display:none">
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
            $('#accept_thesis_supplements_select').on('change', function(){
               if($(this).val() == 1){
                   $('.rejected_supplements_row').css('display', 'none');
                   //Textarea resetelése
                   $('#cause_of_rejecting_thesis_supplements_input').val('');
               }else if($(this).val() == 0){
                   $('.rejected_supplements_row').css('display', 'table-row');
               }
            });

            /**
             * Confirmation modal megnyitása submit előtt
             */
            $('#acceptThesisSupplements .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Szakdolgozat/Diplomamunka mellékleteinek elfogadása.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#acceptThesisSupplements').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#acceptThesisSupplements').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-accept_thesis_supplements').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-accept_thesis_supplements').hide();
                        $('#accept_thesis_supplements_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-accept_thesis_supplements').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
