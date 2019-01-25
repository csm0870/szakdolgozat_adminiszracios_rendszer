<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= Configure::read('title')?></title>
        <?= $this->Html->meta('icon') ?>
        
        <?= $this->Html->css('/bootstrap_v4.1/css/bootstrap.min.css') ?>
        <?= $this->Html->css('/datepicker/bootstrap-datepicker3.min.css') ?>
        <?= $this->Html->css('/fontawesome-free-5.6.1-web/css/all.min') ?>
        <?= $this->Html->css('error_modal.css') ?>
        <?= $this->Html->css('confirmation_modal.css') ?>
        <?= $this->Html->css('logged_in_page.css') ?>

        <?= $this->Html->script('popper.min.js') ?>
        <?= $this->Html->script('jquery-3.3.1.min.js') ?>
        <?= $this->Html->script('/datepicker/bootstrap-datepicker.min.js') ?>
        <?= $this->Html->script('/datepicker/locales/bootstrap-datepicker.hu.min.js') ?>
        <?= $this->Html->script('/fontawesome-free-5.6.1-web/js/all.min') ?>
        <?= $this->Html->script('/bootstrap_v4.1/js/bootstrap.min.js') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div class="main-content-wrapper">
            <div class="main-content">
                <?= $this->element('logged_in_page_header') ?>
                <?= $this->fetch('content') ?>
            </div>
            <!-- Confirmation modal -->
            <div class="modal fade" id="confirmationModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                          <div class="modal-body">
                              <div class="confirmation-modal">
                                  <div class="confirmation-modal-header">
                                      <?= __('Biztosan mented?') ?>
                                  </div>
                                  <div class="confirmation-modal-body">
                                      <div class="msg">...</div>
                                  </div>
                                  <div class="confirmation-modal-footer">
                                      <?= $this->Html->link(__('Mégse'), '#' , ['class' => 'modalBtn cancelBtn border-radius-45px']) ?>
                                      <?= $this->Html->link(__('Mentés'), '#' , ['class' => 'modalBtn saveBtn border-radius-45px']) ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                </div>
            </div>
            <!-- Error modal (Ajax kérésekhez) -->
            <div class="modal fade error-modal" id="error_modal_ajax" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style=" background-color: transparent; border: none;">
                        <div class="modal-body" style="padding: 0;">
                              <div class="error-modal">
                                  <div class="error-modal-header">
                                      <?= __('Hoppá! Valami hiba történt!') ?>
                                  </div>
                                  <div class="error-modal-body">
                                      <div class="error-msg"></div>
                                  </div>
                                  <div class="error-modal-footer">
                                      <?= $this->Html->link(__('Ok'), '#' , ['class' => 'closeBtn']) ?>
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function(){
                //Confirmation modal elrejtése cancel gomba kattintáskor
                $('#confirmationModal .modalBtn.cancelBtn').on('click', function(e){
                    e.preventDefault();
                    $('#confirmationModal').modal('hide');
                });
                
                //Error modal elrejtése ok gomba kattintáskor
                $('#error_modal_ajax .closeBtn').on('click', function(e){
                    e.preventDefault();
                    $('#error_modal_ajax').modal('hide');
                });
                
                //Modal zárásakor megnézzük van-e másik modal nyitva, ha igen akkor a body rárakjuk, hogy nyitva van.
                //Ha ezt nem tennénk rá, akkor levenné ennek a zárásakor róla (body-ról).
                $(document).on('hidden.bs.modal', '.modal', function(){
                    if($('.modal.show').length > 0) $('body').addClass('modal-open');
                });
                
                //Megnyitott modal-ok listája a megnyitások sorrendjében
                var modals = [];
                
                /**
                 * Ha vannak nyitott modalok, akkor azokat sötétítjük. Az utoljára megnyitott pedig aktív marad.
                 */
                $(document).on('show.bs.modal', '.modal', function(){
                    //Modalok sötétítése
                    for(var i = 0; i < modals.length; i++){
                        $(modals[i]).css({filter : 'brightness(50%)',
                                          'overflow-y' : 'hidden'});
                    }
                    //Modal hozzáadása a listához
                    modals.push(this);
                });
                
                /**
                 * A megnyitott modalok közül a legfelsőt "aktívvá" tesszük
                 * Feltétetelés: a lista utolsó eleme volt a legutálja megnyitott modal
                 */
                $(document).on('hide.bs.modal', '.modal', function(){
                    for(var i = 0; i < modals.length; i++){
                        //Modal eltávolítása a listából
                        if(modals[i] === this){
                            modals.splice(i, 1);
                        }
                    }
                    //Utolsó elem láthatóvá tétele
                    if(modals.length > 0) $(modals[modals.length - 1]).css({filter : 'unset',
                                                                            'overflow-y' : 'auto'});
                });
            });
        </script>
    </body>
</html>

