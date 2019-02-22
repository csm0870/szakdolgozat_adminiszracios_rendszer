<div class="container student-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12 col-md-6">
            <p class="<?= $thesisTopic->thesis_topic_status_id == 15 ? 'mb-1' : 'mb-4' ?>">
                <strong><?= __('Állapot') . ': ' ?></strong><?= $thesisTopic->has('thesis_topic_status') ? h($thesisTopic->thesis_topic_status->name) : ''?>
            </p>
            <?php if($thesisTopic->thesis_topic_status_id == 15){ ?>
                <p class="mb-3">
                    <strong><?= __('Elutasítás oka') . ': ' ?></strong><?= h($thesisTopic->cause_of_rejecting_thesis_supplements) ?>
                </p>
            <?php } ?>
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
                <strong><?= __('Belső konzulens') . ': ' ?></strong><?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?>
            </p>
            <p class="mb-1">
                <strong><?= __('Téma címe') . ': ' ?></strong><?= h($thesisTopic->title) ?>
            </p>
        </div>
        <div class="col-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#supplementCollapse" aria-expanded="true" aria-controls="collapseOne">
                                <?= ($thesisTopic->is_thesis === null ? __('Szakdolgozat') : ($thesisTopic->is_thesis === true) ? __('Szakdolgozat') : __('Diplomamunka')) . '&nbsp;' .  __('mellékletek') ?>
                                <i class="fas fa-angle-down fa-lg" id="supplement_arrow_down"></i>
                                <i class="fas fa-angle-up fa-lg d-none" id="supplement_arrow_up"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="supplementCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                             <ul>
                                <?php
                                    foreach($thesisTopic->thesis_supplements as $supplement){
                                        if(!empty($supplement->file)){
                                            echo '<li>' .
                                                    $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id, 'prefix' => false], ['target' => '_blank']) .
                                                 '</li>';
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($thesisTopic->thesis_topic_status_id == 14){ ?>
            <div class="col-12 mt-3 text-center">
                <?= $this->Form->button(__('Mellékletek elfogadása'), ['class' => 'btn btn-primary acceptThesisSupplementsBtn border-radius-45px']) ?>
            </div>
        <?php } ?>
    </div>
</div>
<!-- Diplomakurzus első félévének teljesítésének rögzítése modal -->
<div class="modal fade" id="acceptThesisSupplementsModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="accept_thesis_supplements_container">

                </div>
            </div>
        </div>
  </div>
</div>
<script>
    $(function(){
        $('#thesis_topics_index_menu_item').addClass('active');
        
        //Tartalom lekeérése a "diplomakurzus első félévének teljesítésének rögzítése" modalba
        $.ajax({
            url: '<?= $this->Url->build(['action' => 'acceptThesisSupplements', $thesisTopic->id], true) ?>',
            cache: false
        })
        .done(function( response ) {
            $('#accept_thesis_supplements_container').html(response.content);
        });
        
        $('.acceptThesisSupplementsBtn').on('click', function(e){
            e.preventDefault();
            $('#acceptThesisSupplementsModal').modal('show');
        });
        
        /**
         * Accordion megjelenítésekor nyíl cseréje
         */
        $('#supplementCollapse').on('show.bs.collapse', function () {
            $('#supplement_arrow_up').removeClass('d-none');
            $('#supplement_arrow_down').addClass('d-none');
        });
        
        /**
         * Accordion eltüntetésekor nyíl cseréje
         */
        $('#supplementCollapse').on('hide.bs.collapse', function () {
            $('#supplement_arrow_down').removeClass('d-none');
            $('#supplement_arrow_up').addClass('d-none');
        });
        
        //Confirmation modal elfogadás előtt
        $('.acceptBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elfogadod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Mellékletek elfogadása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elfogadás') ?>').css('background-color', '#71D0BD');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#acceptThesisSupplements').trigger('submit');
            });
        });
        
        //Confirmation modal elutasítás előtt
        $('.rejectBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .header').text('<?= __('Biztosan elutasítod?') ?>');
            $('#confirmationModal .msg').text('<?= __('Mellékletek elutasítása.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Elutasítás') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#rejectThesisSupplements').trigger('submit');
            });
        });
    });
</script>
