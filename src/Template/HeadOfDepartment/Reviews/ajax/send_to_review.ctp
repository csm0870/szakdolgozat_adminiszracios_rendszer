<div class="form-modal headOfDepartment-sendToReview">
    <?= $ok === true ? $this->Form->create(null, ['id' => 'sendToReviewForm']) : '' ?>
    <div class="form-header text-center">
        <?= __('Bírálatra küldés') ?>
    </div>
    <div class="form-body text-justify">
        <?php if($ok === true){ ?>
            <?= __('A bíráló adatai:') ?>
            <p class="mb-1">
                <strong><?= __('Név') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->name) ?>
            </p>
            <p class="mb-1">
                <strong><?= __('Email') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->email) ?>
            </p>
            <p class="mb-1">
                <strong><?= __('Munkahely') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->workplace) ?>
            </p>
            <p class="mb-4">
                <strong><?= __('Pozició') . ': ' ?></strong><?= h($thesisTopic->review->reviewer->position) ?>
            </p>
            <p>
                <?= __('Bírálatra küldés után (amelyek a téma részleteinél elérhetők lesznek) egy email és hozzá tartozó jelszó generálódik, - ha még az adott bírálónak nincs - amelyeket el kell eljuttatni a bírálónak a belépéshez. ' .
                    'Ezzel a bíráló beléphet, majd elérheti a megfelelő dokumentumokat a bírálathoz, és megteheti a bírálatot.' .
                    '<br/>' .
                    'Titkos dolgozat esetén először a titkosítási kérelmet kell letöltenie, majd aláírva feltölteni, amelyet el kell fogadni, és ezután férhet hozzá a dolgozathoz.')
                ?>
            </p>
        <?php }else{ ?>
            <p class="text-center">
                <?= $error_msg; ?>
            </p>
        <?php } ?>
    </div>
    <div class="form-footer text-center">
        <?= $ok === true ? $this->Form->button(__('Bírálatra küldés'), ['type' => 'button', 'role' => 'button', 'class' => 'btn btn-success submitBtn border-radius-45px']) : '' ?>
    </div>
    <?= $ok === true ? $this->Form->end() : '' ?>
    <div class="overlay overlay-send_to_review" style="display:none">
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
            $('#sendToReviewForm .submitBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan bírálatra küldöd?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Küldés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Bírálatra küldés.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#sendToReviewForm').trigger('submit');
                });
            });

            //consultationOccasionAddForm ajaxform
            $('#sendToReviewForm').ajaxForm({
                replaceTarget: false,
                target: null,
                beforeSubmit: function(arr, $form, options) {
                    $('.overlay-send_to_review').show();
                },
                success: function (response, textStatus, jqXHR, $form){
                    if(response.saved == false){
                        $('.overlay-send_to_review').hide();
                        $('#send_to_review_container').html(response.content);
                        $('#error_modal_ajax .error-msg').html(response.error_ajax);
                        $('#error_modal_ajax').modal('show');
                    }else{
                        location.href = '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], true)?>';
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $('.overlay-send_to_review').hide();
                    $('#error_modal_ajax .error-msg').html('<?= __('Hiba történt mentés közben. Próbálja újra!') . '<br/>' . __('Hiba') . ': ' ?>' + errorThrown);
                    $('#error_modal_ajax .error-code').text('-1000');
                    $('#error_modal_ajax').modal('show');
                }
            });
        });
    </script>
<?php } ?>
