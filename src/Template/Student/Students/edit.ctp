<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h4><?= __('Adatok megadása') ?></h4>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
        <div class="col-12">
            <?php
                $this->Form->templates(['inputContainer' => '<div class="form-group">{{content}}</div>',
                                        'inputContainerError' => '<div class="form-group">{{content}}{{error}}</div>']);
                
                echo $this->Form->create($student);
                echo $this->Form->control('name', ['class' => 'form-control', 'label' => ['text' => __('Név')], 'readonly' => !$can_add_topic]);
                echo $this->Form->control('address', ['class' => 'form-control', 'label' => ['text' => __('Cím')], 'readonly' => !$can_add_topic]);
                echo $this->Form->control('neptun', ['class' => 'form-control', 'label' => ['text' => __('Neptun kód')], 'readonly' => !$can_add_topic]);
                echo $this->Form->control('email', ['class' => 'form-control', 'label' => ['text' => __('Email cím')], 'readonly' => !$can_add_topic]);
                echo $this->Form->control('phone_number', ['class' => 'form-control', 'label' => ['text' => __('Telefonszám')], 'placeholder' => __('+36701234567 formátumban.'), 'readonly' => !$can_add_topic]);
                echo $this->Form->control('specialisation', ['class' => 'form-control', 'label' => ['text' => __('Specializáció')], 'readonly' => !$can_add_topic]);
                echo $this->Form->control('course_id', ['options' => $courses, 'class' => 'form-control', 'label' => ['text' => __('Szak')], 'disabled' => !$can_add_topic]);
                echo $this->Form->control('course_level_id', ['options' => $courseLevels, 'class' => 'form-control', 'label' => ['text' => __('Képzés szintje')], 'disabled' => !$can_add_topic]);
                echo $this->Form->control('course_type_id', ['options' => $courseTypes, 'class' => 'form-control', 'label' => ['text' => __('Tagozat')], 'disabled' => !$can_add_topic]);
                if($can_add_topic === true) echo $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary border-radius-45px', 'type' => 'submit']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#student_data_menu_item').addClass('active');
    });
</script>
