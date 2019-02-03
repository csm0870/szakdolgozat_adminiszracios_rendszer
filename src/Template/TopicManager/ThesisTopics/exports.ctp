<div class="container thesisTopic-exports topicManager">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma adatok exportálása') ?></h4>
        </div>
        <div class="col-12">
            <div class="row">
                <?= $this->Form->control('years', ['options' => $years, 'value' => $year->id, 'id' => 'year_select', 'label' => __('Tanév') . ':', 'templates' => ['formGroup' => '{{label}}&nbsp;{{input}}', 'inputContainer' => '<div class="col-12 col-sm-6 text-center timeInput">{{content}}</div>']]) ?>
                <?= $this->Form->control('semester', ['options' => [__('Ősz'), __('Tavasz')], 'id' => 'semester_select', 'label' => __('Félév') . ':', 'templates' => ['formGroup' => '{{label}}&nbsp;{{input}}', 'inputContainer' => '<div class="col-12 col-sm-6 text-center timeInput">{{content}}</div>']]) ?>
            </div>
        </div>
        <div class="col-12">
            <?= $this->Html->link(__('Exportálás CSV-be'), ['controller' => 'ThesisTopics', 'action' => 'exportCsv', $year->id, $semester],
                                  ['class' => 'btn btn-info border-radius-45px', 'target' => '_blank', 'id' => 'export_csv']) ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#thesis_topic_exports').addClass('active');
        
        $('#year_select, #semester_select').on('change', function(){
            var year_id = $('#year_select').val();
            var semester = $('#semester_select').val();
            
            $('#export_csv').attr('href', '<?= $this->Url->build(['controller' => 'ThesisTopics', 'action' => 'exportCsv'], true) ?>' + '/' + year_id + '/' + semester);
        });
    });
</script>