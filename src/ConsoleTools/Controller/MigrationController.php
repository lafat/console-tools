<?php

namespace ConsoleTools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Exception\RuntimeException;
use ConsoleTools\Model\Migration;

/**
 * Controller for console operations as create, upgrate and current migrations
 * 
 * @author     V.Leontiev <vadim.leontiev@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 * @since      php 5.3 or higher
 * @see        https://github.com/newage/console-tools
 */
class MigrationController extends AbstractActionController
{
    
    /**
     * Destination to folder with migration files
     * 
     * @var string
     */
    const MIGRATION_FOLDER = '/config/migrations/';

    /**
     * Create new migration file
     * 
     * @throws RuntimeException
     */
    public function createAction()
    {
        $console = $this->getServiceLocator()->get('console');
        
        if (!$console instanceof Console) {
            throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }
        
        $migrationPath = getcwd() . self::MIGRATION_FOLDER;
        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0777);
        }
        
        $migrationName = time() . '.php';
        
        $migrationModel = new \ConsoleTools\Model\Migration();
        $migrationContent = $migrationModel->generate();
        
        file_put_contents($migrationPath . $migrationName, $migrationContent);
        
        $console->writeLine('Create new migration file: ' . $migrationName, Color::GREEN);
    }
    
    /**
     * Upgrade/downgrade to migration
     * 
     * @throws RuntimeException
     */
    public function upgradeAction()
    {
        $console = $this->getServiceLocator()->get('console');
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        
        
        if (!$console instanceof Console) {
            throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }
    }
    
    /**
     * Show current applied migration number
     * 
     */
    public function currentAction()
    {
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console) {
            throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');
        }
        
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $model = new Migration($adapter);
        $currentMigration = $model->current();
        
        $console->writeLine('Current applied migration: ' . $currentMigration, Color::GREEN);
    }
}
