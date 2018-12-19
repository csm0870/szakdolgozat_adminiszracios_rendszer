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
                                        'action' => 'login'
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
                                        'action' => 'home'],
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
            return $this->redirect(["controller" => "Pages", "action" => "home"]);
        }
        
        //Layout beállítása, ha a listában lévő controllerek hívják meg
        if(in_array($this->getRequest()->getParam('controller'), ['Information'])){
            $this->viewBuilder()->setLayout('logged_in_page');
        }
        
        if(!\Cake\Core\Configure::check('title')){
            \Cake\Core\Configure::write('title',__('Szakdolgozat adminisztrációs rendszer'));
        }
    }
}
