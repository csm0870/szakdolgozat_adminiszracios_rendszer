<div class="container headOfDepartment-checkReview">
    <div class="row">
        <div class="col-12 text-center">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('A dolgozat bírálata') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12 mb-4 mt-2">
            <?php
                if($thesisTopic->thesis_topic_status_id == \Cake\Core\Configure::read('ThesisTopicStatuses.UnderReview') && $thesisTopic->has('review')){
                    echo '<br/><strong>' .  __('Állapot') . ': ' . '</strong>';
                    if($thesisTopic->review->review_status == 4){
                        echo __('A bírálati lap feltöltés véglegesítve. A bírálat a tanszékvezető ellenőrzésére vár.');
                        if($thesisTopic->review->cause_of_rejecting_review !== null){
                            echo '<br/><strong>Az előző bírálat elutasításának oka:</strong>&nbsp;' . h($thesisTopic->review->cause_of_rejecting_review);
                        }
                    }
                    elseif($thesisTopic->review->review_status == 5){
                        echo __('A bírálat elutasítva, a dolgozat ismét bírálható.');
                        echo '<br/><strong>Elutasítás oka:</strong>&nbsp;' . h($thesisTopic->review->cause_of_rejecting_review);
                    }
                }
            ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            
                echo $this->Form->control('structure_and_style_point', ['class' => 'form-control', 'id' => 'structure_and_style_point_input', 'label' => ['text' => __('A dolgozat szerkezete, stílusa')],
                                                                        'value' => $thesisTopic->review->structure_and_style_point, 'readonly' => true]);
                echo $this->Form->control('cause_of_structure_and_style_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A dolgozat szerkezetére, stílusára adott pontszám indoklása')],
                                                                                 'value' => $thesisTopic->review->cause_of_structure_and_style_point, 'readonly' => true,
                                                                                 'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                 'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('processing_literature_point', ['class' => 'form-control', 'id' => 'processing_literature_point_input', 'label' => ['text' => __('Szakirodalom feldolgozása')],
                                                                          'readonly' => true, 'value' => $thesisTopic->review->processing_literature_point]);
                echo $this->Form->control('cause_of_processing_literature_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('Szakirodalom feldolgozására adott pontszám indoklása')],
                                                                                   'readonly' => true, 'value' => $thesisTopic->review->cause_of_processing_literature_point,
                                                                                   'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                   'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('writing_up_the_topic_point', ['class' => 'form-control', 'id' => 'writing_up_the_topic_point_input', 'label' => ['text' => __('A téma kidolgozásának színvonala')],
                                                                         'value' => $thesisTopic->review->writing_up_the_topic_point, 'readonly' =>true]);
                echo $this->Form->control('cause_of_writing_up_the_topic_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A téma kidolgozásának színvonalára adott pontszám indoklása')],
                                                                                  'value' => $thesisTopic->review->cause_of_writing_up_the_topic_point, 'readonly' => true,
                                                                                  'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                  'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('practical_applicability_point', ['class' => 'form-control', 'id' => 'practical_applicability_point_input', 'label' => ['text' => __('A dolgozat gyakorlati alkalmazhatósága')],
                                                                            'value' => $thesisTopic->review->practical_applicability_point, 'readonly' => true]);
                echo $this->Form->control('cause_of_practical_applicability_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A dolgozat gyakorlati alkalmazhatóságára adott pontszám indoklása')],
                                                                                     'value' => $thesisTopic->review->cause_of_practical_applicability_point, 'readonly' => true,
                                                                                     'templates' => ['inputContainer' => '<div class="form-group">{{content}}</div>',
                                                                                                     'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']]);
                echo '<p class="text-right mb-1">'  . '<strong>' . __('Összpontszám') . '</strong>:&nbsp;<span id="total_points">0<span></p>';
                echo '<p class="text-right">'  . '<strong>' . __('Jegy') . '</strong>:&nbsp;<span id="grade">1<span></p>';
                echo $this->Form->control('general_comments', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('Általános megjegyzések')], 'readonly' => true, 'value' => $thesisTopic->review->general_comments]);

                echo '<h5 class="mt-5 mb-4">' . __('Kérdések')  . '</h5>';

                echo '<div id="question_container">';
                $i = 1;
                foreach($thesisTopic->review->questions as $question){
                    echo $this->Form->control("questions[$i][question]", ['type' => 'textarea', 'value' => $question->question, 'class' => 'form-control', 'readonly' => true,
                                                                          'label' => ['text' => "$i."]]);
                    ++$i;
                }
                echo '</div>';
            ?>
            </div>
        <div class="col-12">
            <fieldset class="border-1-grey p-3 text-center">
                <legend class="w-auto"><?= __('Műveletek') ?></legend>
                <?php
                    if($thesisTopic->review->review_status == 4)
                        echo $this->Html->link(__('Bírálat elfogadása'), '#', ['class' => 'btn btn-secondary acceptReviewBtn border-radius-45px mb-2']) . '<br/>';
                
                    if(in_array($thesisTopic->review->review_status, [4, 5, 6]))
                        echo $this->Html->link(__('Feltöltött bírálati lap letöltése'), ['action' => 'getReviewDoc', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px mb-2', 'target' => '__blank']);

                ?>
            </fieldset>
        </div>
    </div>
</div>
<?php if($thesisTopic->review->review_status == 4){ ?>
    <!-- Bírálat elfogadása modal -->
    <div class="modal fade" id="acceptReviewModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="accept_review_container">

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
        (function calculatePintsAndGrade(){
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
        })();
    
        <?php if($thesisTopic->review->review_status == 4){ ?>
            //Tartalom lekeérése a "bírálat elfogadása" modalba
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviews', 'action' => 'acceptReview', $thesisTopic->id], true) ?>',
                cache: false
            })
            .done(function( response ) {
                $('#accept_review_container').html(response.content);
            });

            $('.headOfDepartment-checkReview .acceptReviewBtn').on('click', function(e){
                e.preventDefault();
                $('#acceptReviewModal').modal('show');
            });
        <?php } ?>
    });
</script>

