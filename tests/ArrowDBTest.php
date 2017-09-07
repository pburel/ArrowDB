<?php
/**
 * A Laravel package to inteface with ArrowDB - Appcelerator Cloud Services
 *
 * @package   ArrowDB
 * @version   1.0.0
 * @author    Clément Blanco
 * @license   MIT License
 * @copyright 2016 Clément Blanco
 * @link      http://github.com/ArrowDB/laravel-arrowdb
 */

namespace Claymm\ArrowDB\Test;

use Claymm\ArrowDB\ArrowDB;
use \Mockery as m;

class ArrowDBTest extends \PHPUnit_Framework_TestCase
{
    public function teardown()
    {
        m::close();
    }

    public function testDelete()
    {

    }

    public function testGet()
    {
        $mock = m::mock('ArrowDB');
        $mock->shouldReceive('get')
             ->with('users/search.json')
             ->andReturnUsing(function ($data) {
                if ($data->meta->status === 'ok') {
                    return true;
                } else {
                    return false;
                }
             });
    }

    public function testPost()
    {
        // Code goes here...
    }

    public function testPut()
    {
        // Code goes here...
    }
}
