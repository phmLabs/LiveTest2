<?php

namespace Unit\LiveTest\Config\Tags\TestSuite;

/**
 * UseSessions test case.
 */
use LiveTest\Config\Parser\Parser;

use LiveTest\Config\TestSuite;

use LiveTest\Config\Tags\TestSuite\UseSessions;

class UseSessionsTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var UseSessions
   */
  private $UseSessions;

  private $config;

  private $sessionNames = array ('Nils', 'Fabian');
  const TEST_CASE_NAME = 'NilsUndFabiansTestCase';

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp()
  {
    parent::setUp();

    $sessionsNames = $this->sessionNames;
    $this->config = new TestSuite();
    $this->config->createTestCase(self::TEST_CASE_NAME, '', array ());
    $parser = new Parser('');

    $this->UseSessions = new UseSessions($sessionsNames, $this->config, $parser);
  }

  public function testSessionAdding()
  {
    $this->UseSessions->process();

    $testCaseConfigs = $this->config->getTestCases();
    $testCaseConfig = $testCaseConfigs[self::TEST_CASE_NAME];

    $sessions = $testCaseConfig->getSessionNames();
    $this->assertEquals($this->sessionNames, $sessions);
  }
}