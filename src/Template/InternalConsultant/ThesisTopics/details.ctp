<div class="container internalConsultant-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-details-body">
                <div class="col-12 col-md-6">
                    <p class="mb-4">
                        <strong><?= __('Állapot') . ': ' ?></strong><?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Neptun kód') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Szak') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?>
                    </p>
                    <p class="mb-1">
                        <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center">
                    <?= $this->Html->link(__('Konzultációk kezelése'), ['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px margin-bottom-10px']) ?>
                    <?php if($thesisTopic->thesis_topic_status_id == 8){ ?>
                        <?= $this->Html->link(__('Diplomakurzus első félévének teljesítésének rögzítése'), '#', ['class' => 'btn btn-secondary border-radius-45px setFirstThesisSubjectCompletedBtn']) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Diplomakurzus első félévének teljesítésének rögzítése modal -->
<div class="modal fade" id="setFirstThesisSubjectCompletedModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="set_first_thesis_subject_completed_container">

                </div>
            </div>
        </div>
  </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
        //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" modalba
        $.ajax({
            url: '<?= $this->Url->build(['action' => 'setFirstThesisSubjectCompleted', $thesisTopic->id], true) ?>',
            cache: false
        })
        .done(function( response ) {
            $('#set_first_thesis_subject_completed_container').html(response.content);
        });
        
        $('.internalConsultant-thesisTopics-details .setFirstThesisSubjectCompletedBtn').on('click', function(e){
            e.preventDefault();
            $('#setFirstThesisSubjectCompletedModal').modal('show');
        });
    });
</script>