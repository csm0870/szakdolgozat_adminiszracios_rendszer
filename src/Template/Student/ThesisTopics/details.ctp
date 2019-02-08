<div class="container student-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12 supplements-container">
            <h5><?= __('Mellékletek') . ':' ?></h5>
            <?php 
                foreach($thesisTopic->thesis_supplements as $supplement){
                    if(!empty($supplement->file)){ 
                        echo '<p>' .
                                $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id], ['target' => '_blank']) .
                             '</p>';
                    }
                }
             ?>
        </div>
        <?php if($student->course_id == 1){ //Ha mérnökinformatikus?>
            <div class="col-12 mt-4">
                <h5><?= __('Záróvizsga tárgyak') . ':' ?></h5>
                <div class="row">
                    <?php $i = 0;
                        foreach($student->final_exam_subjects as $subject){ $i++; if($i >= 4) break; ?>
                            <div class="col-12 col-md-6 mb-2">
                                <strong><?=  __('Tárgy neve') ?>:&nbsp;</strong><?= h($subject->name) ?><br/>
                                <strong><?= __('Tanár(ok)') ?>:&nbsp;</strong><?= h($subject->teachers) ?><br/>
                                <strong><?=  __('Tanév') ?>:&nbsp;</strong><?= $subject->has('year') ? h($subject->year->name) : '' ?><br/>
                                <strong><?=  __('Félév') ?>:&nbsp;</strong><?= $subject->semester !== null ? ($subject->semester === true ? __('Tavasz') : __('Ősz')) : '-' ?>
                            </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <?php 
                            if($i > 0){ //Van záróvizsgatárgy 
                                echo $this->Html->link(__('Záróvizsga tárgy jelölő lap'), ['controller' => 'ThesisTopics', 'action' => 'finalExamSubjectsDoc', $thesisTopic->id], ['class' => 'btn btn-info border-radius-45px', 'target' => '_blank']);
                            } 
                        ?>
                    </div>
                </div>
                
            </div>
        <?php } ?>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
    });
</script>
