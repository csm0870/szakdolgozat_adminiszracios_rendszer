<?php
namespace App\Controller;

use App\Controller\AppController;

class InstallController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event){
        $this->Auth->allow(['install']);
    }
    
    public function install(){
        //Telepítás után a következő sornál a kommentet el kell távolítani (ez átirányít a kezdőoldalra, célja, hogyha már telepítve van, akkor ha élesbe van a rendszer, akkor ne lehessen telepíteni)
        return $this->redirect('/');
        
        //SQL fájl beolváasása és lefutattésa a DB-ben
        
        set_time_limit(7200);
        
        $conn = \Cake\Datasource\ConnectionManager::get('install');
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file(CONFIG . DS . 'db_tables.sql');
        // Loop through each line
        foreach ($lines as $line){
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if(substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                $conn->execute($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        
        echo "Installed successfully!";
        $this->autoRender = false;
    }
}
