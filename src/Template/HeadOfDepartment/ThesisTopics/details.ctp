<div class="container headOfDepartment-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-details-body">
                <div class="col-12">
                    <p>
                        <strong><?= __('Hallgató neve') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Neptun kód') . ': ' ?></strong><?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?>
                    </p>
                    <p>
                        <strong><?= __('Belső konzulens') . ': ' ?></strong><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?>
                    </p>
                    <p>
                        <strong><?= __('Szak') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés szintje') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Képzés típusa') . ': ' ?></strong><?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?>
                    </p>
                    <p>
                        <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
                    </p>
                    <?php if($thesisTopic->thesis_topic_status_id == 9){ ?>
                        <p>
                            <strong><?= __('Diplomakurzus első félévét nem teljesítette') . ': ' ?></strong>
                            <?= $this->Html->link(__('Döntés a folytatásról'), '#', ['class' => 'decideToContinueAfterFailedFirstThesisSubjectBtn']) ?>
                        </p>
                    <?php } ?>
                </div>
                <!--<div class="col-12 col-md-6 text-center">
                </div>-->
            </div>
        </div>
    </div>
</div>
<!-- Diplomakurzus első félévének teljesítésének rögzítése modal -->
<div class="modal fade" id="decideToContinueAfterFailedFirstThesisSubjectModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="decide_to_continue_after_failed_first_thesis_subject_container">

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
            url: '<?= $this->Url->build(['action' => 'decideToContinueAfterFailedFirstThesisSubject', $thesisTopic->id], true) ?>',
            cache: false
        })
        .done(function( response ) {
            $('#decide_to_continue_after_failed_first_thesis_subject_container').html(response.content);
        });
        
        $('.headOfDepartment-thesisTopics-details .decideToContinueAfterFailedFirstThesisSubjectBtn').on('click', function(e){
            e.preventDefault();
            $('#decideToContinueAfterFailedFirstThesisSubjectModal').modal('show');
        });
    });
</script>