<div class="form-modal internalConsultant-setThesisGrade">
    <?= $ok ? $this->Form->create($thesisTopic, ['id' => 'setThesisGradeForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Dolgozat értékelése') ?>
    </div>
    <div class="form-body">
        <?php if($ok === false){ ?>
            <p class="text-center">
                <?= $error_msg ?>
            </p>
        <?php }else{ ?>
            <table>
                <tr>
                    <td class="text-center">
                        <?= $this->Form->control('internal_consultant_grade', ['label' => __('Jegy') . ": ", 'error' => false,
                                                                               'options' => [2 => 2, 3 => 3, 4 => 4, 5 => 5],
                                                                               'templates' => ['formGroup' => '{{label}}&nbsp;&nbsp;{{input}}']]) ?>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Mentés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? '' : $this->Form->end() ?>
    <div class="overlay overlay-set_thesis_grade" style="display:none">
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
            $('#setThesisGradeForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Dolgozat értékelésének mentése.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#setThesisGradeForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#setThesisGradeForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-set_thesis_grade').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-set_thesis_grade').hide();
                        $('#set_thesis_grade_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-set_thesis_grade').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
