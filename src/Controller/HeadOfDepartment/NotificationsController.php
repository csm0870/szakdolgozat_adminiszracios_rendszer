<?php
namespace App\Controller\HeadOfDepartment;

use App\Controller\AppController;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\NotificationsTable $Notifications
 *
 * @method \App\Model\Entity\Notification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotificationsController extends AppController
{
    /**
     * Értesítések lista
     */
    public function index(){
        $notifications = $this->Notifications->find('all', ['conditions' => ['user_id' => $this->Auth->user('id')],
                                                            'order' => ['created' => 'DESC']]);
        $this->set(compact('notifications'));
    }
    
    /**
     * Értesítés lekérése, illetve olvasottá tétele
     * 
     * @param type $id Értesítés azonosító
     */
    public function getNotification($id = null){
        $notification = $this->Notifications->find('all', ['conditions' => ['Notifications.id' => $id, 'user_id' => $this->Auth->user('id')]])->first();
        
        $success = true;
        $subject = '';
        $message = '';
        $date = '';
        
        if(!empty($notification)){
            $subject = $notification->subject;
            $message = $notification->message;
            $date = empty($notification->created) ? '' : $notification->created->i18nFormat('yyyy.MM.dd HH:mm:ss');
        
            if($notification->unread === true){
                $notification->unread = false;
                if(!$this->Notifications->save($notification)) $success = false;
            }
        }
                
        $unread_notifications_count = $this->Notifications->find('all', ['conditions' => ['Notifications.user_id' => $this->Auth->user('id'), 'Notifications.unread' => true]])->count();
        
        $has_unread = $unread_notifications_count > 0 ? true : false;
        
        $this->set(compact('success', 'subject', 'message', 'date', 'has_unread'));
        $this->set('_serialize', ['success', 'subject', 'message', 'date', 'has_unread']);
    }
    
    /**
     * Értesítések törlése
     */
    public function delete(){
        $ok = true;
        if($this->getRequest()->is(['post', 'delete'])){
            $notifications_ids = $this->getRequest()->getData('notifications_ids');
            
            foreach($notifications_ids as $id){
                $notification = $this->Notifications->find('all', ['conditions' => ['Notifications.id' => $id, 'Notifications.user_id' => $this->Auth->user('id')]])->first();
                if(!empty($notification)){
                    if(!$this->Notifications->delete($notification)){
                        $this->Flash->error(__('Törlés sikertelen. Próbálja újra!'));
                    }
                }
            }
        }
        
        if($ok === true) $this->Flash->success(__('Törlés sikeres.'));
        
        return $this->redirect(['action' => 'index']);
    }
}
