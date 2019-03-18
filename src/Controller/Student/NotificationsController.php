<?php
namespace App\Controller\Student;

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
    public function index(){
        $notifications = $this->Notifications->find('all', ['conditions' => ['user_id' => $this->Auth->user('id')],
                                                            'order' => ['created' => 'DESC']]);
        $this->set(compact('notifications'));
    }
    
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
        
        
        $this->set(compact('success', 'subject', 'message', 'date'));
        $this->set('_serialize', ['success', 'subject', 'message', 'date']);
    }
}
