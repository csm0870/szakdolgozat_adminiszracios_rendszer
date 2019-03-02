<div class="container thesisTopics-index internalConsultant-thesisTopics-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Témák') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row thesisTopics-index-body">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover topics-table">
                                <tr>
                                    <th><?= __('Hallgató neve') ?></th>
                                    <th><?= __('Neptun kód') ?></th>
                                    <th><?= __('Állapot') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($students as $student){ ?>
                                    <tr>
                                        <td><?= h($student->name) ?></td>
                                        <td><?= h($student->neptun) ?></td>
                                        <td>
                                            <?php
                                                if($student->final_exam_subjects_status === 2) echo __('Hallgató véglegesítette. Ellenőrzésre vár.');
                                                elseif($student->final_exam_subjects_status === 3) echo __('Elfogadva.');
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?= in_array($student->final_exam_subjects_status, [2, 3, 4]) ? $this->Html->link(__('Részletek'), ['controller' => 'FinalExamSubjects', 'action' => 'details', $student->id], ['class' => 'btn btn-info border-radius-45px']) : '' ?> 
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#final_exam_subjects_index_menu_item').addClass('active');
    });
</script>