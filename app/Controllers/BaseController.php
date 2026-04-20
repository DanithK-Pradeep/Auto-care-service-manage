<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $data = [];
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);
        $this->data['navItems'] = [
            ['label' => 'Home', 'url' => site_url('/'), 'match' => 'home', 'button' => false],
            [

                'label' => 'Services',
                'url'   => site_url('services'),
                'match' => 'services',
                'button' => false,

                'dropdown' => [
                    [
                        'label' => 'Oil Change',
                        'url'   => site_url('services/oil-change'),
                    ],
                    [
                        'label' => 'Car Wash',
                        'url'   => site_url('services/car-wash'),
                    ],
                    [
                        'label' => 'Engine Repair',
                        'url'   => site_url('services/engine-repair'),
                    ],
                    [
                        'label' => 'Brake Service',
                        'url'   => site_url('services/brake-service'),
                    ],

                ],


            ],
            ['label' => 'About', 'url' => site_url('about'), 'match' => 'about', 'button' => false],
            ['label' => 'Contact', 'url' => site_url('contact'), 'match' => 'contact', 'button' => false],
            ['label' => 'Book Service', 'url' => site_url('#book'), 'match' => 'book', 'button' => true],
            ['label' => 'Login', 'url' => site_url('employee/login'), 'match' => 'login', 'button' => false],
            
        ];

        
        $this->data['currentUrl'] = current_url();
    

       
    }
}
