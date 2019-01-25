<div class="container internalConsultant-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-details-body">
                <div class="col-12 col-md-6">
                    <p>
                        <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Neptun kód') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Szak') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés típúsa') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                    </p>
                    <?php if($thesisTopic->first_thesis_subject_completed !== null){ ?>
                        <p>
                            <strong><?= __('Diplomakurzus első félévét teljesítette') . ': ' ?></strong><?= h($thesisTopic->first_thesis_subject_completed == true ? __('Igen') : __('Nem')) ?>
                        </p>
                    <?php } ?>
                </div>
                <div class="col-12 col-md-6 text-center">
                    <?= $this->Html->link(__('Konzultációk kezelése'), ['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id], ['class' => 'btn btn-secondary border-radius-45px margin-bottom-10px']) ?>
                    <?php if($thesisTopic->first_thesis_subject_completed === null){ ?>
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
        $('#thesis_topic_index_menu_item').addClass('active');
        
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