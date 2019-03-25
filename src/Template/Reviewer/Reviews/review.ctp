<div class="container reviewer-review">
    <div class="row">
        <div class="col-12 text-center">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Dolgozat bírálata') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12 mb-4 mt-2">
            <?php
                if($thesisTopic->is_thesis === true){
                    echo '<strong>' . __('Szakdolgozat bírálati útmutató') . '</strong>:&nbsp';
                    if(empty($thesis_review_guide) || empty($thesis_review_guide->file)){
                        echo __('Nem elérhető az útmutató.');
                    }else echo $this->Html->link(__('letöltés'), ['controller' => 'Documents', 'action' => 'downloadFile', $thesis_review_guide->id, 'prefix' => false], ['target' => '__blank']);
                }else{
                    echo '<strong>' .  __('Diplomamunka bírálati útmutató') . '</strong>:&nbsp';
                    if(empty($diploma_review_guide) || empty($diploma_review_guide->file)){
                        echo __('Nem elérhető az útmutató.');
                    }else echo $this->Html->link(__('letöltés'), ['controller' => 'Documents', 'action' => 'downloadFile', $diploma_review_guide->id, 'prefix' => false], ['target' => '__blank']);
                }
                
                echo '<br/><strong>' .  __('Állapot') . ': ' . '</strong>';
                if($thesisTopic->thesis_topic_status_id == 23 && $thesisTopic->has('review')){
                    if($thesisTopic->confidential === true && $thesisTopic->review->confidentiality_contract_status != 4){
                        if($thesisTopic->review->confidentiality_contract_status == null) echo __('A titoktartási szerződés feltöltésére vár.');
                        elseif($thesisTopic->review->confidentiality_contract_status == 1) echo __('A titoktartási szerződés feltölve, véglegesítésre vár.');
                        elseif($thesisTopic->review->confidentiality_contract_status == 2) echo __('A titoktartási szerződés véglegesítve, tanszékvezető ellenőrzésére vár.');
                        elseif($thesisTopic->review->confidentiality_contract_status == 3) echo __('A titoktartási szerződés elutasítva, újra feltölthető.');
                    }else{
                        if($thesisTopic->review->review_status == null) echo __('A dolgozat bírálatra vár.');
                        elseif($thesisTopic->review->review_status == 1) echo __('A bírálat véglegesítésre vár.');
                        elseif($thesisTopic->review->review_status == 2) echo __('A bírálat véglegesítve, bírálati lap feltöltésére vár.');
                        elseif($thesisTopic->review->review_status == 3) echo __('A bírálati lap feltöltve, véglegesítésre vár.');
                        elseif($thesisTopic->review->review_status == 4) echo __('A bírálati lap feltöltés véglegesítve. A bírálat a tanszékvezető ellenőrzésére vár.');
                        elseif($thesisTopic->review->review_status == 5){
                            echo __('A bírálat elutasítva, a dolgozat ismét bírálható.');
                            echo '<br/><strong>Elutasítás oka:</strong>&nbsp;' . h($thesisTopic->review->cause_of_rejecting_review);
                        }
                        
                        if(in_array($thesisTopic->review->review_status, [1, 2, 3]) && $thesisTopic->review->cause_of_rejecting_review !== null){
                            echo '<br/><strong>Az előző bírálat elutasításának oka:</strong>&nbsp;' . h($thesisTopic->review->cause_of_rejecting_review);
                        }
                    }
                }
            ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);

                echo $this->Form->create($thesisTopic->review, ['id' => 'reviewForm', 'class' => 'row']);
            ?>
            <div class="col-12">
                <?php
                    echo $this->Form->control('structure_and_style_point', ['class' => 'form-control', 'id' => 'structure_and_style_point_input', 'label' => ['text' => __('A dolgozat szerkezete, stílusa')],
                                                                            'placeholder' => __('max. 10 pont'), 'min' => 0, 'max' => 10, 'required' => true, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6])]);
                    echo $this->Form->control('cause_of_structure_and_style_point', ['class' => 'form-control', 'label' => ['text' => __('A dolgozat szerkezetére, stílusára adott pontszám indoklása')],
                                                                                     'required' => true, 'maxlength' => 280, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                                     'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                     'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                    echo $this->Form->control('processing_literature_point', ['class' => 'form-control', 'id' => 'processing_literature_point_input', 'label' => ['text' => __('Szakirodalom feldolgozása')],
                                                                              'placeholder' => __('max. 10 pont'), 'min' => 0, 'max' => 10, 'required' => true, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6])]);
                    echo $this->Form->control('cause_of_processing_literature_point', ['class' => 'form-control', 'label' => ['text' => __('Szakirodalom feldolgozására adott pontszám indoklása')],
                                                                                       'required' => true, 'maxlength' => 280, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                                       'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                       'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                    echo $this->Form->control('writing_up_the_topic_point', ['class' => 'form-control', 'id' => 'writing_up_the_topic_point_input', 'label' => ['text' => __('A téma kidolgozásának színvonala')],
                                                                             'placeholder' => __('max. 20 pont'), 'min' => 0, 'max' => 20, 'required' => true, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6])]);
                    echo $this->Form->control('cause_of_writing_up_the_topic_point', ['class' => 'form-control', 'label' => ['text' => __('A téma kidolgozásának színvonalára adott pontszám indoklása')],
                                                                                   'required' => true, 'maxlength' => 280, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                                   'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                   'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                    echo $this->Form->control('practical_applicability_point', ['class' => 'form-control', 'id' => 'practical_applicability_point_input', 'label' => ['text' => __('A dolgozat gyakorlati alkalmazhatósága')],
                                                                                'placeholder' => __('max. 10 pont'), 'min' => 0, 'max' => 10, 'required' => true, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6])]);
                    echo $this->Form->control('cause_of_practical_applicability_point', ['class' => 'form-control', 'label' => ['text' => __('A dolgozat gyakorlati alkalmazhatóságára adott pontszám indoklása')],
                                                                                   'required' => true, 'maxlength' => 280, 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                                   'templates' => ['inputContainer' => '<div class="form-group">{{content}}</div>',
                                                                                                   'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']]);
                    echo '<p class="text-right mb-1">'  . '<strong>' . __('Összpontszám') . '</strong>:&nbsp;<span id="total_points">0<span></p>';
                    echo '<p class="text-right">'  . '<strong>' . __('Jegy') . '</strong>:&nbsp;<span id="grade">1<span></p>';
                    echo $this->Form->control('general_comments', ['class' => 'form-control', 'label' => ['text' => __('Általános megjegyzések')], 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                   'placeholder' => __('minimum 10 sor a word dokumentum szerint'), 'required' => true, 'minlength' => 490]);

                    echo '<h5 class="mt-5 mb-4">' . __('Kérdések') . ' (' . __('minimum 3 darab') . '):' .  '</h5>';

                    echo '<div id="question_container">';
                    $i = 1;
                    foreach($thesisTopic->review->questions as $question){
                        echo $this->Form->control("questions[$i][id]", ['type' => 'hidden', 'value' => $question->id]);
                        echo $this->Form->control("questions[$i][question]", ['type' => 'textarea', 'value' => $question->question, 'class' => 'form-control', 'readonly' => in_array($thesisTopic->review->review_status, [2, 3, 4, 6]),
                                                                              'label' => ['class' => 'order-labels', 'text' => "<span class='label-orders' data-order='$i'>$i</span>." . (in_array($thesisTopic->review->review_status, [2, 3, 4, 6]) ? '' : ('&nbsp;&nbsp;&nbsp;' . $this->Html->link('(' . __('kérdés törlése') . ')', '#', ['class' => 'removeQuestion']))), 'data-order' => $i, 'escape' => false], 'required' => $i <= 3 ? true : false]);
                        ++$i;
                    }

                    if(!in_array($thesisTopic->review->review_status, [2, 4])){
                        for($j = $i; $j < 4; $j++){
                            echo $this->Form->control("questions[$j][question]", ['type' => 'textarea', 'class' => 'form-control', 'label' => ['class' => 'order-labels', 'text' => "<span class='label-orders' data-order='$j'>$j</span>." . '&nbsp;&nbsp;&nbsp;' . $this->Html->link('(' . __('kérdés törlése') . ')', '#', ['class' => 'removeQuestion']), 'escape' => false], 'required' => true]);
                        }
                    }
                    echo '</div>';
                    
                    if(!in_array($thesisTopic->review->review_status, [2, 3, 4, 6])){
                        echo '<p class="text-center">' . $this->Html->link('<i class="fas fa-plus-circle fa-2x" style="vertical-align: middle"></i>&nbsp;' . __('Kérdés hozzáadása'), '#', ['id' => 'add_question', 'escape' => false, 'class' => 'addQuestionLink']) . '</p>';
                        echo '<br/><br/>';
                    }
                ?>
            </div>
            <div class="col-12">
                <fieldset class="border-1-grey p-3 text-center">
                    <legend class="w-auto"><?= __('Műveletek') ?></legend>
                    <?php
                        if(!in_array($thesisTopic->review->review_status, [2, 3, 4, 6]))
                            echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px mb-2', 'type' => 'submit']) . '<br/>';

                        if($thesisTopic->review->review_status == 1)
                            echo $this->Html->link(__('Bírálat véglegesítése'), '#', ['class' => 'btn btn-success finalizeReviewBtn border-radius-45px mb-2']) . '<br/>';

                        if($thesisTopic->review->review_status == 3)
                            echo $this->Html->link(__('Bírálat lap feltöltésének véglegesítése'), '#', ['class' => 'btn btn-success finalizeUploadedReviewDocBtn border-radius-45px mb-2']) . '<br/>';
                        
                        if(in_array($thesisTopic->review->review_status, [2, 3]))
                            echo $this->Html->link(__('Bírálat lap feltöltése'), '#', ['class' => 'btn btn-success uploadReviewDocBtn border-radius-45px mb-2']) . '<br/>';
                         
                        if(in_array($thesisTopic->review->review_status, [2, 3]))
                            echo $this->Html->link(__('Bírálati lap letöltése'), ['action' => 'reviewDoc', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px mb-2', 'target' => '__blank']);

                    ?>
                </fieldset>
            </div>
        <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?php if(in_array($thesisTopic->review->review_status, [2, 3])){ ?>
    <!-- Bírálati lap feltöltése modal -->
    <div class="modal fade" id="uploadReviewDocModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="upload_review_doc_container">

                    </div>
                </div>
            </div>
      </div>
    </div>
<?php } ?>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        /**
         * Összpontszám és a jegy kiszámolása
         * 
         * @return {undefined}
         */
        function calculatePintsAndGrade(){
            var structure_and_style_point = parseInt(isNaN($('#structure_and_style_point_input').val()) || $('#structure_and_style_point_input').val() == '' ? 0 : $('#structure_and_style_point_input').val());
            var processing_literature_point = parseInt(isNaN($('#processing_literature_point_input').val()) || $('#processing_literature_point_input').val() == '' ? 0 : $('#processing_literature_point_input').val());
            var writing_up_the_topic_point = parseInt(isNaN($('#writing_up_the_topic_point_input').val()) || $('#writing_up_the_topic_point_input').val() == '' ? 0 : $('#writing_up_the_topic_point_input').val());
            var practical_applicability_point = parseInt(isNaN($('#practical_applicability_point_input').val()) || $('#practical_applicability_point_input').val() == '' ? 0 : $('#practical_applicability_point_input').val());

            var total_points = structure_and_style_point + processing_literature_point +
                               writing_up_the_topic_point + practical_applicability_point;

            $('#total_points').text(total_points);

            //Ha valamelyik pontszám 0 akkor a jegy "1"-es
            if(structure_and_style_point == 0)  $('#grade').text('1');
            else if(processing_literature_point == 0) $('#grade').text('1');
            else if(writing_up_the_topic_point == 0) $('#grade').text('1');
            else if(practical_applicability_point == 0) $('#grade').text('1');
            else{
                if(total_points >= 45) $('#grade').text('5');
                else if(total_points < 45 && total_points >= 38) $('#grade').text('4');
                else if(total_points < 38 && total_points >= 31) $('#grade').text('3');
                else if(total_points < 31 && total_points >= 26) $('#grade').text('2');
                else $('#grade').text('1');
            }
        }
        
        <?php if(!in_array($thesisTopic->review->review_status, [2, 4])){ ?>
            function setLabelOrder(){
                var labels = $('.label-orders').toArray();

                labels.sort(function(first, second){
                    return parseInt($(first).data('order')) - parseInt($(second).data('order'));
                });

                for(var i = 0; i < labels.length; i++){
                    $(labels[i]).text(i + 1);
                }
            }

            //Pontok változtatásakor az eredmény újraszámítása
            $('#structure_and_style_point_input, #processing_literature_point_input, ' +
               '#writing_up_the_topic_point_input, #practical_applicability_point_input').on('keyup', function(){
                calculatePintsAndGrade();
            });

            var count_of_questions = <?= $j - 1 ?>;

            /**
             * Kérdés hozzáadása
             */
            $('#add_question').on('click', function(e){
                e.preventDefault();
                ++count_of_questions;

                var delete_link = count_of_questions > 3 ? '&nbsp;&nbsp;&nbsp;<a href="#" class="removeQuestion" data-id="' + count_of_questions + '">(kérdés törlése)</a>' : '';

                $('#question_container').append('<div class="form-group">' +
                                                    '<label><span class="label-orders" data-order="' + count_of_questions + '"></span>.' + delete_link + '</label>' +
                                                    '<textarea name="questions[' + count_of_questions + '][question]" class="form-control" rows="5"></textarea>' +
                                                '</div>');
                setLabelOrder();
            });
        
            /**
             * Kérdés eltávolítása
             */
            $(document).on('click', '.removeQuestion', function(e){
                 e.preventDefault();
                 $(this).parent().parent().remove();
                 setLabelOrder();
            });
            
            /**
            * Confirmation modal megnyitása submit előtt
            */
            $('#reviewForm .submitBtn').on('click', function(e){
                e.preventDefault();

                //Formvalidáció manuális meghívása
                if($('#reviewForm')[0].reportValidity() === false) return;

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Bírálat mentése. Mentés után még módosíthatóak az adatok.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    $('#reviewForm').trigger('submit');
                });
            });
        <?php } ?>
        
        calculatePintsAndGrade();
        <?php if($thesisTopic->review->review_status == 1){ ?>
            /**
            * Confirmation modal megnyitása véglegesítés előtt
            */
            $('#reviewForm .finalizeReviewBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Véglegesítés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Bírálat véglegesítése. Ha módosított az adatokon, és úgy nyomja meg a véglegesítést, akkor azok nem mentődnek el, csak a mentés gombra kattintva módosulnak az adatok. Végelgesítés után az adatok már nem módosíthatóak. Véglegesítés után egy word dokumentum generálódik, amelyet ki kell nyomtatni, aláírni, majd PDF-ben feltölteni.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['action' => 'finalizeReview', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
    
        <?php if(in_array($thesisTopic->review->review_status, [2, 3])){ ?>
            //Tartalom lekeérése a "bíralti lap feltöltése" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'uploadReviewDoc', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#upload_review_doc_container').html(response.content);
            });

            $('.reviewer-review .uploadReviewDocBtn').on('click', function(e){
                e.preventDefault();
                $('#uploadReviewDocModal').modal('show');
            });
        <?php } ?>
        
        <?php if($thesisTopic->review->review_status == 3){ ?>
            /**
            * Confirmation modal megnyitása véglegesítés előtt
            */
            $('#reviewForm .finalizeUploadedReviewDocBtn').on('click', function(e){
                e.preventDefault();

                $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan véglegesíted?') ?>');
                $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Véglegesítés') ?>').css('background-color', '#71D0BD');
                //Save gomb eventjeinek resetelése cserével
                $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                $('#confirmationModal .msg').text('<?= __('Bírálati lap véglegesítése. Véglegesítés után az bírálati nem már nem tölthető fel. A véglegesítés után a tanszékvezető fogja ellenőrizni, majd elfogadja vagy elutasítja. Elutasítás esetén a bírálati lehetőség újra aktív lesz.') ?>');

                $('#confirmationModal').modal('show');

                $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                    location.href = '<?= $this->Url->build(['action' => 'finalizeUploadedReviewDoc', $thesisTopic->id], true) ?>';
                });
            });
        <?php } ?>
    });
</script>

