<div class="container student-checkReview">
    <div class="row">
        <div class="col-12 text-center">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'details', $thesisTopic->id], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('A dolgozat bírálata') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12 mt-2">
            <?php
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            
                echo $this->Form->control('cause_of_structure_and_style_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A dolgozat szerkezetére, stílusára adott pontszám indoklása')],
                                                                                 'value' => $thesisTopic->review->cause_of_structure_and_style_point, 'readonly' => true,
                                                                                 'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                 'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('cause_of_processing_literature_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('Szakirodalom feldolgozására adott pontszám indoklása')],
                                                                                   'readonly' => true, 'value' => $thesisTopic->review->cause_of_processing_literature_point,
                                                                                   'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                   'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('cause_of_writing_up_the_topic_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A téma kidolgozásának színvonalára adott pontszám indoklása')],
                                                                                  'value' => $thesisTopic->review->cause_of_writing_up_the_topic_point, 'readonly' => true,
                                                                                  'templates' => ['inputContainer' => '<div class="form-group mb-5">{{content}}</div>',
                                                                                                  'inputContainerError' => '<div class="form-group mb-5">{{content}}{{error}}</div>']]);
                echo $this->Form->control('cause_of_practical_applicability_point', ['type' => 'textarea', 'class' => 'form-control', 'label' => ['text' => __('A dolgozat gyakorlati alkalmazhatóságára adott pontszám indoklása')],
                                                                                     'value' => $thesisTopic->review->cause_of_practical_applicability_point, 'readonly' => true,
                                                                                     'templates' => ['inputContainer' => '<div class="form-group">{{content}}</div>',
                                                                                                     'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']]);
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
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>

