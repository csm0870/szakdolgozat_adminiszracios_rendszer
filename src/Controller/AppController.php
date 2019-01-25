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

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        
        $this->loadComponent('Auth',
                    ['loginAction' => [
                                        'controller' => 'Users',
                                        'action' => 'login',
                                        'prefix' => false
                                    ],
                    'authError' => __('Be kell lépned, ahhoz, hogy ezt az oldalt lásd!'),
                    'authenticate' => [
                        'Form' => [
                            'fields' => ['username' => 'email']
                        ]
                    ],
                    'loginRedirect' => [
                                        'controller' => 'Pages',
                                        'action' => 'dashboard'
                                        ],
                    'logoutRedirect' => [
                                        'controller' => 'Pages',
                                        'action' => 'home',
                                        'prefix' => false],
                    'storage' => 'Session']);

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }
    
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        
        if ($this->Auth->user("id") == null && $this->request->action != "home" && $this->request->action != "login") {
            return $this->redirect(["controller" => "Pages", "action" => "home", 'prefix' => false]);
        }
        
        //Layout beállítása, ha a listában lévő controllerek hívják meg
        if(in_array($this->getRequest()->getParam('controller'), ['Information', 'ThesisTopics', 'Students', 'Consultations', 'ConsultationOccasions']) || ($this->getRequest()->getParam('controller') == 'Pages' && $this->getRequest()->getParam('action') == 'dashboard')){
            $this->viewBuilder()->setLayout('logged_in_page');
        }
        
        //Ha a belépett oldalon vagyunk, akkor a usert átadjuk a view-nak
        if($this->viewBuilder()->getLayout() == 'logged_in_page'){
            $user_id = $this->Auth->user('id');
            $this->loadModel('Users');
            $logged_in_user = $this->Users->get($user_id);

            $this->set('logged_in_user', $logged_in_user);
        }
        
        if(!\Cake\Core\Configure::check('title')){
            \Cake\Core\Configure::write('title',__('Szakdolgozat adminisztrációs rendszer'));
        }
        
        if(!\Cake\Core\Configure::check('CakePdf')){
            \Cake\Core\Configure::write('CakePdf', [
                'engine' => ['className' => 'CakePdf.WkHtmlToPdf',
                             //'binary' => CONFIG . 'wkhtmltopdf' . DS . 'bin' . DS .'wkhtmltopdf', //ngix-re
                            ]
                ]);
        }
    }
}
