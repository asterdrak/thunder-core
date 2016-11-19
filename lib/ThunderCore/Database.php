<?php

namespace ThunderCore;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;





/**
* class which handles doctrine functions
*/
class Database
{

  /**
   * config for YAMLMetadataConfiguration for set_doctrine_config, internal 
   */
  private $config;
  /**
   * sets up entity menager from doctrine, external
   */
  private $entityManager;
  /**
   * environment object reference, internal
   */
  private $environment;
  
  /**
   * gets $environment object as parameter, add $environment to class param
   * sets up YAML Metadata Configuration
   * sets up entity menager as private field
   */
  public function __construct($environment)
  {
      $environment->register_environment_variable('doctrine_model_paths', glob('app/models/*'));
      $this->environment = $environment;

      $this->set_doctrine_config();
      $this->set_entity_manager();

  }

  public function getEntityManager() {
    return $this->entityManager;
  }

  // ----------------------------------------
  //           private methods
  // ----------------------------------------

  private function set_doctrine_config() {
    $paths = $this->environment['doctrine_model_paths'];
    $isDevMode = $this->environment['isDevMode'];

    $this->config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
  }

  private function set_entity_manager() {
    $this->entityManager = EntityManager::create($this->environment['dbParams'], $this->config);
  }
}


