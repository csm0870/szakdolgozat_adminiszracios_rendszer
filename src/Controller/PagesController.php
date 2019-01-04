<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['home']);
    }

    /**
     * Home
     * 
     * @param type $type
     */
    public function home($type = null){
        $admin = false;
        if($type == 'admin') $admin = true;
        
        $this->set(compact('admin'));
    }
    
    /**
     * Dashboard
     */
    public function dashboard(){
        //Hallgatói adatellenőrzés
        if($this->Auth->user('group_id') == 6){
            $this->loadModel('Students');
            $data = $this->Students->checkStundentData($this->Auth->user('id'));
            if($data['success'] === false){
                $this->Flash->error(__('Adja meg az adatit a továbblépéshez!'));
                return $this->redirect(['controller' => 'Students', 'action' => 'studentEdit', $data['student_id']]);
            }
        }
    }
}
