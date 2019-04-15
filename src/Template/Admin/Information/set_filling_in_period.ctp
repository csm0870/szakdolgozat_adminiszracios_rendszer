<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h4><?= __('A témaengedélyezők kitöltési időszakának beállítása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php 
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                          'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
            ?>
            <?= $this->Form->create($info, ['id' => 'setFillingInPeriodForm']) ?>
            <?= $this->Form->control('filling_in_topic_form_begin_date', ['label' => ['text' => __('Kezdő dátum')], 'autocomplete' => 'off', 'aria-describedby' => "date-1" , 'value' => empty($info->filling_in_topic_form_begin_date) ? '' : $this->Time->format($info->filling_in_topic_form_begin_date, 'yyyy-MM-dd'), 'class' => 'datepicker form-control', 'type' => 'text', 'templates' => ['input' => ' <div class="input-group-prepend"><span class="input-group-text" id="date-1"><i class="fa fa-calendar"></i></span><input type="{{type}}" name="{{name}}"{{attrs}}/></div>']]) ?>
            <?= $this->Form->control('filling_in_topic_form_end_date', ['label' => ['text' => __('Befejezési dátum')], 'autocomplete' => 'off', 'aria-describedby' => "date-2", 'value' => empty($info->filling_in_topic_form_end_date) ? '' : $this->Time->format($info->filling_in_topic_form_end_date, 'yyyy-MM-dd'), 'class' => 'datepicker form-control', 'type' => 'text', 'templates' => ['input' => '<div class="input-group-prepend"><span class="input-group-text" id="date-2"><i class="fa fa-calendar"></i></span><input type="{{type}}" name="{{name}}"{{attrs}}/></div>']]) ?>
            <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary submitBtn border-radius-45px', 'type' => 'submit']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#set_topic_filling_in_period_menu_item').addClass('active');
        $('.datepicker').datepicker({
            language:'hu',
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
        
        /**
        * Confirmation modal megnyitása submit előtt
        */
        $('#setFillingInPeriodForm .submitBtn').on('click', function(e){
            e.preventDefault();

            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan mented?') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Mentés') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
            $('#confirmationModal .msg').text('<?= __('A témaengedélyők kitöltési időszakának beállítása.') ?>');

            $('#confirmationModal').modal('show');

            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#setFillingInPeriodForm').trigger('submit');
            });
        });
    });
</script>