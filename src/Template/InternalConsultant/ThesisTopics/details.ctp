<div class="container internalConsultant-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma részletei') ?></h4>
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
                </div>
                <div class="col-12 col-md-6">
                    <?= $this->Html->link(__('Konzultációk kezelése'), ['controller' => 'Consultations', 'action' => 'index', $thesisTopic->id], ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topic_index_menu_item').addClass('active');
    });
</script>