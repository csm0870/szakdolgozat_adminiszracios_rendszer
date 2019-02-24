<div class="container student-thesisTopics-details">
    <div class="row">
        <div class="col-12 text-center page-title">
            <?= $this->Html->link('<i class="fas fa-arrow-alt-circle-left fa-lg"></i>' . '&nbsp;' . __('Vissza'), ['controller' => 'ThesisTopics', 'action' => 'index'], ['escape' => false, 'class' => 'backBtn float-left border-radius-45px', 'title' => __('Vissza')]) ?>
            <h4><?= __('Téma részletei') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
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
                                                    $this->Html->link($supplement->file, ['controller' => 'ThesisSupplements', 'action' => 'downloadFile', $supplement->id], ['target' => '_blank']) .
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
    </div>
</div>
<script>
    $(function(){
        $('#topics_menu_item').addClass('active');
        $('#thesis_topics_index_menu_item').addClass('active');
        
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
    });
</script>
