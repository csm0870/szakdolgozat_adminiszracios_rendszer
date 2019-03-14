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
        if(in_array($this->getRequest()->getParam('controller'), ['Information', 'ThesisTopics', 'Students', 'Consultations', 'ConsultationOccasions', 'FinalExamSubjects', 'Reviewers', 'OfferedTopics', 'Reviews']) || ($this->getRequest()->getParam('controller') == 'Pages' && $this->getRequest()->getParam('action') == 'dashboard')){
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
    
    /**
     * Adott mappába(path) a fájlnév alapján nevet ad a fájlnak és kiszedi a spaceket a névből, ha már létezik a megadott név akkor számmal bővíti, ha még nem létezik
     * akkor marad ugyanaz.
     * 
     * @param string $file_name Fájl neve
     * @param string $path Fájl elérési útja
     * @param string $ext Fájl kiterjesztése, ha null, akkor a fájlnévből veszi ki
     * @return boolean|string FALSE ha nincs path vagy file_name, amugy a fájl neve
     */
    protected function addFileName($file_name = null, $path = null, $ext = null){
        if(empty($file_name) || empty($path)) return false;
        $trans = $translate = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD);
        $file_name = $trans->transliterate(str_replace(' ', '', $file_name));
        
        $files = array_diff(scandir($path), array('..', '.'));
        if($ext === null) $name = substr($file_name ,0, strrpos($file_name, "."));
        else $name = $file_name;
        if($ext === null) $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_name = $name . "."  . $ext;
        $i = 0;
        $ok = false;

        while($ok !== true){
            if(in_array($new_name, $files)){
                $new_name = $name . "-" . $i . "." . $ext;
                ++$i;
            }else{
                $ok = true;
            }
        }
        
        return $new_name;
    }
}
