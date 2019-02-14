<div class="container">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Bírálók kezelése') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover topics-table">
                                <tr>
                                    <th><?= __('Név') ?></th>
                                    <th><?= __('Email') ?></th>
                                    <th><?= __('Műveletek') ?></th>
                                </tr>
                                <?php foreach($reviewers as $reviewer){ ?>
                                    <tr>
                                        <td><?= h($reviewer->name) ?></td>
                                        <td><?= h($reviewer->email) ?></td>
                                        <td class="text-center">
                                            <?php
                                                echo $this->Html->link('<i class="fas fa-edit fa-lg"></i>', '#', ['class' => 'iconBtn editBtn', 'data-id' => $reviewer->id, 'escape' => false, 'title' => __('Szerkesztés')]);
                                                echo $this->Html->link('<i class="fas fa-trash fa-lg"></i>', '#', ['escape' => false, 'title' => __('Törlés'), 'class' => 'iconBtn deleteBtn', 'data-id' => $reviewer->id]);
                                                echo $this->Form->postLink('', ['action' => 'delete', $reviewer->id], ['style' => 'display: none', 'id' => 'deleteReviewerForm_' . $reviewer->id]);
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                <div class="col-12">
                    <?= $this->Html->link(__('Új bíráló hozzáadása'), '#', ['class' => 'btn btn-outline-secondary btn-block border-radius-45px', 'id' => 'add_new_reviewer']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bíráló hozzáadása modal -->
<div class="modal fade" id="reviewerAddModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="reviewer-add">

                </div>
            </div>
        </div>
  </div>
</div>
<!-- Bíráló szerkesztése modal -->
<div class="modal fade" id="reviewerEditModal" data-focus="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <div id="reviewer-edit">

                </div>
            </div>
        </div>
  </div>
</div>
<script>
    $(function(){
        $('#reviewers_index_menu_item').addClass('active');
        
        //Tartalom lekeérése a hozzáadáshoz
        $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Reviewers', 'action' => 'add']) ?>',
                cache: false
        })
        .done(function(response) {
            $('#reviewer-add').html(response.content);
        });
        
        //Bíráló hozzáadása popup megnyitása
        $('#add_new_reviewer').on('click', function(e){
            e.preventDefault();
            $('#reviewerAddModal').modal('show');
        });
        
        //Bíráló szerkesztése
        $('.editBtn').on('click', function(e){
            e.preventDefault();
            
            var id = $(this).data('id');
            
            //Szerkesztési oldal lekérése
            $.ajax({
                    url: '<?= $this->Url->build(['action' => 'edit'], true) ?>' + '/' + id,
                    cache: false
            })
            .done(function(response){
                    $('#reviewer-edit').html(response.content);
                    $('#reviewerEditModal').modal('show');
            });
        });
        
        //Törléskor confirmation modal a megerősítésre
        $('.deleteBtn').on('click', function(e){
            e.preventDefault();
            
            $('#confirmationModal .confirmation-modal-header').text('<?= __('Biztosan törlöd?') ?>');
            $('#confirmationModal .msg').text('<?= __('Bíráló törlése.') ?>');
            $('#confirmationModal .modalBtn.saveBtn').text('<?= __('Törlés') ?>').css('background-color', 'red');
            //Save gomb eventjeinek resetelése cserével
            $('#confirmationModal .modalBtn.saveBtn').replaceWith($('#confirmationModal .modalBtn.saveBtn').first().clone());
                        
            $('#confirmationModal').modal('show');
            
            var id = $(this).data('id');
            $('#confirmationModal .modalBtn.saveBtn').on('click', function(e){
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $('#deleteReviewerForm_' + id).trigger('click');
            });
        });
    });
</script>