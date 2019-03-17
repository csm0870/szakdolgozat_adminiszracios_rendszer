<div class="container finalExamOrganizer-students-index">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Hallgatók') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th><?= __('Hallgató neve') ?></th>
                                    <th><?= __('Neptun kód') ?></th>
                                    <th><?= __('Szak') ?></th>
                                    <th><?= __('Tagozat') ?></th>
                                    <th><?= __('Képzési szint') ?></th>
                                </tr>
                                <?php foreach($students as $student){ ?>
                                    <tr>
                                        <td><?= h($student->name) ?></td>
                                        <td><?= h($student->neptun) ?></td>
                                        <td><?= $student->has('course') ? h($student->course->name) : '' ?></td>
                                        <td><?= $student->has('course_type') ? h($student->course_type->name) : '' ?></td>
                                        <td><?= $student->has('course_level') ? h($student->course_level->name) : '' ?></td>
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
        $('#students_menu_item').addClass('active');
        $('#students_index_menu_item').addClass('active');
    });
</script>