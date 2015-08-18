<?php

namespace OutsideForce\Test\TestCase\Routing\Filter;

use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\TestCase;
use OutsideForce\Routing\Filter\OutsideForceFilter;
use OutsideForce\Test\TestClass\App\Controller\AppController;
use OutsideForce\Test\TestClass\App\Controller\PostsController;

class OutsideForceFilterTest extends TestCase
{
    /**
     * @test
     */
    public function testBeforeDispatchAppController()
    {
        $request = new Request(['url' => '/app']);
        $response = new Response();
        $event = new Event('Dispatcher.beforeDispatch', $this, compact('response', 'request'));
        $event->data['controller'] = new AppController($request, $response);

        $filter = new OutsideForceFilter();
        try {
            $filter->beforeDispatch($event);
            $this->fail('no exception');
        } catch (Exception $e) {
            $class = 'Cake\Routing\Exception\MissingControllerException';
            $this->assertTrue(($e instanceof $class));
        }
    }

    /**
     * @test
     */
    public function testBeforeDispatchExcludeAppController()
    {
        $request = new Request(['url' => '/app']);
        $response = new Response();
        $event = new Event('Dispatcher.beforeDispatch', $this, compact('response', 'request'));
        $event->data['controller'] = new AppController($request, $response);

        $filter = new OutsideForceFilter(['classes' => 'PostsController']);
        try {
            $filter->beforeDispatch($event);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail('has exception');
        }
    }

    /**
     * @test
     */
    public function testBeforeDispatchPostsController()
    {
        $request = new Request(['url' => '/posts']);
        $response = new Response();
        $event = new Event('Dispatcher.beforeDispatch', $this, compact('response', 'request'));
        $event->data['controller'] = new PostsController($request, $response);

        $filter = new OutsideForceFilter();
        try {
            $filter->beforeDispatch($event);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail('has exception');
        }
    }

    /**
     * @test
     */
    public function testBeforeDispatchIncludePostsController()
    {
        $request = new Request(['url' => '/posts']);
        $response = new Response();
        $event = new Event('Dispatcher.beforeDispatch', $this, compact('response', 'request'));
        $event->data['controller'] = new PostsController($request, $response);

        $filter = new OutsideForceFilter(['classes' => ['PostsController']]);
        try {
            $filter->beforeDispatch($event);
            $this->fail('no exception');
        } catch (Exception $e) {
            $class = 'Cake\Routing\Exception\MissingControllerException';
            $this->assertTrue(($e instanceof $class));
        }
    }
}
