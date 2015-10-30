<?php
/**
 * MSRestApi
 *
 * Copyright (c) 2015, Dmitry Mamontov <d.slonyara@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Dmitry Mamontov nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   ms-restapi
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.1.2
 */
/**
 * MSRestApi - The main class
 *
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version   Release: 1.1.2
 * @link      https://github.com/dmamontov/ms-restapi/
 * @link      http://wiki.moysklad.ru/wiki/REST-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81_%D1%81%D0%B8%D0%BD%D1%85%D1%80%D0%BE%D0%BD%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8_%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D1%85
 * @since     Class available since Release 1.1.2
 */

class MSRestApi
{
    /**
     * URL from RestAPI
     */
    const URL = 'https://online.moysklad.ru/exchange/rest/ms/xml';

    /**
     * Methods
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Login access to API
     * @var string
     * @access protected
     */
    protected $login;

    /**
     * Password access to API
     * @var string
     * @access protected
     */
    protected $password;

    /**
     * Curl instance
     * @var resource
     * @access protected
     */
    protected $curl;

    /**
     * Curl timeout
     * @var integer
     * @access protected
     */
    protected $timeout = 300;

    /**
     * Class constructor
     * @param string $login
     * @param string $key
     * @return void
     * @access public
     * @final
     */
    final public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->curl = curl_init();
    }

    /**
     * Get unit of measurement.
     * 
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function uomGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Uom/%s', self::URL, $uuid));
    }

    /**
     * Create unit of measurement.
     *
     * @param SimpleXMLElement $uom
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function uomCreate(SimpleXMLElement $uom)
    {
        $parameters['data'] = $uom;

        return $this->curlRequest(sprintf('%s/Uom', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete unit of measurement.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function uomDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Uom/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list unit of measurement.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function uomGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Uom/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list unit of measurement.
     *
     * @param SimpleXMLElement $uoms
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function uomUpdateList(SimpleXMLElement $uoms)
    {
        $parameters['data'] = $uoms;

        return $this->curlRequest(sprintf('%s/Uom/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Get good.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Good/%s', self::URL, $uuid));
    }

    /**
     * Create good.
     *
     * @param SimpleXMLElement $good
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodCreate(SimpleXMLElement $good)
    {
        $parameters['data'] = $good;

        return $this->curlRequest(sprintf('%s/Good', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete good.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Good/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list good.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;
    
        return $this->curlRequest(sprintf('%s/Good/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list good.
     *
     * @param SimpleXMLElement $goods
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodUpdateList(SimpleXMLElement $goods)
    {
        $parameters['data'] = $goods;
    
        return $this->curlRequest(sprintf('%s/Good/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list good.
     *
     * @param SimpleXMLElement $goods
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodDeleteList(SimpleXMLElement $goods)
    {
        $parameters['data'] = $goods;

        return $this->curlRequest(sprintf('%s/Good/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get good folder.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/GoodFolder/%s', self::URL, $uuid));
    }

    /**
     * Create good folder.
     *
     * @param SimpleXMLElement $goodFolder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderCreate(SimpleXMLElement $goodFolder)
    {
        $parameters['data'] = $goodFolder;

        return $this->curlRequest(sprintf('%s/GoodFolder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete good folder.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/GoodFolder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list good folder.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/GoodFolder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list good folder.
     *
     * @param SimpleXMLElement $goodFolders
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderUpdateList(SimpleXMLElement $goodFolders)
    {
        $parameters['data'] = $goodFolders;

        return $this->curlRequest(sprintf('%s/GoodFolder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list good folder.
     *
     * @param SimpleXMLElement $goodFolders
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function goodFolderDeleteList(SimpleXMLElement $goodFolders)
    {
        $parameters['data'] = $goodFolders;

        return $this->curlRequest(sprintf('%s/GoodFolder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get service.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Service/%s', self::URL, $uuid));
    }

    /**
     * Create service.
     *
     * @param SimpleXMLElement $service
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceCreate(SimpleXMLElement $service)
    {
        $parameters['data'] = $service;

        return $this->curlRequest(sprintf('%s/Service', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete service.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Service/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list service.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Service/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list service.
     *
     * @param SimpleXMLElement $service
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceUpdateList(SimpleXMLElement $service)
    {
        $parameters['data'] = $service;

        return $this->curlRequest(sprintf('%s/Service/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list service.
     *
     * @param SimpleXMLElement $service
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function serviceDeleteList(SimpleXMLElement $service)
    {
        $parameters['data'] = $service;

        return $this->curlRequest(sprintf('%s/Service/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get warehouse.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Warehouse/%s', self::URL, $uuid));
    }

    /**
     * Create warehouse.
     *
     * @param SimpleXMLElement $warehouse
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseCreate(SimpleXMLElement $warehouse)
    {
        $parameters['data'] = $warehouse;

        return $this->curlRequest(sprintf('%s/Warehouse', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete warehouse.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Warehouse/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list warehouse.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Warehouse/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list warehouse.
     *
     * @param SimpleXMLElement $warehouse
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseUpdateList(SimpleXMLElement $warehouse)
    {
        $parameters['data'] = $warehouse;

        return $this->curlRequest(sprintf('%s/Warehouse/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list warehouse.
     *
     * @param SimpleXMLElement $warehouse
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function warehouseDeleteList(SimpleXMLElement $warehouse)
    {
        $parameters['data'] = $warehouse;

        return $this->curlRequest(sprintf('%s/Warehouse/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get company.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Company/%s', self::URL, $uuid));
    }

    /**
     * Create company.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyCreate(SimpleXMLElement $company)
    {
        $parameters['data'] = $company;

        return $this->curlRequest(sprintf('%s/Company', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete company.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Company/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list company.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Company/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list company.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyUpdateList(SimpleXMLElement $company)
    {
        $parameters['data'] = $company;

        return $this->curlRequest(sprintf('%s/Company/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list company.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function companyDeleteList(SimpleXMLElement $company)
    {
        $parameters['data'] = $company;

        return $this->curlRequest(sprintf('%s/Company/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get my company.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/MyCompany/%s', self::URL, $uuid));
    }

    /**
     * Create my company.
     *
     * @param SimpleXMLElement $myCompany
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyCreate(SimpleXMLElement $myCompany)
    {
        $parameters['data'] = $myCompany;

        return $this->curlRequest(sprintf('%s/MyCompany', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete my company.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/MyCompany/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list my company.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/MyCompany/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list my company.
     *
     * @param SimpleXMLElement $myCompany
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyUpdateList(SimpleXMLElement $myCompany)
    {
        $parameters['data'] = $myCompany;

        return $this->curlRequest(sprintf('%s/MyCompany/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list my company.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function myCompanyDeleteList(SimpleXMLElement $myCompany)
    {
        $parameters['data'] = $myCompany;

        return $this->curlRequest(sprintf('%s/MyCompany/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get person.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Person/%s', self::URL, $uuid));
    }

    /**
     * Create person.
     *
     * @param SimpleXMLElement $person
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personCreate(SimpleXMLElement $person)
    {
        $parameters['data'] = $person;

        return $this->curlRequest(sprintf('%s/Person', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete person.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Person/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list person.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Person/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list person.
     *
     * @param SimpleXMLElement $person
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personUpdateList(SimpleXMLElement $person)
    {
        $parameters['data'] = $person;

        return $this->curlRequest(sprintf('%s/Person/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list person.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function personDeleteList(SimpleXMLElement $person)
    {
        $parameters['data'] = $person;

        return $this->curlRequest(sprintf('%s/Person/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get employee.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Employee/%s', self::URL, $uuid));
    }

    /**
     * Create employee.
     *
     * @param SimpleXMLElement $employee
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeCreate(SimpleXMLElement $employee)
    {
        $parameters['data'] = $employee;

        return $this->curlRequest(sprintf('%s/Employee', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete employee.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Employee/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list employee.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Employee/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list employee.
     *
     * @param SimpleXMLElement $employee
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeUpdateList(SimpleXMLElement $employee)
    {
        $parameters['data'] = $employee;

        return $this->curlRequest(sprintf('%s/Employee/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list employee.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function employeeDeleteList(SimpleXMLElement $employee)
    {
        $parameters['data'] = $employee;

        return $this->curlRequest(sprintf('%s/Employee/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get country.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Country/%s', self::URL, $uuid));
    }

    /**
     * Create country.
     *
     * @param SimpleXMLElement $country
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryCreate(SimpleXMLElement $country)
    {
        $parameters['data'] = $country;

        return $this->curlRequest(sprintf('%s/Country', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete country.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Country/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list country.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Country/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list country.
     *
     * @param SimpleXMLElement $country
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryUpdateList(SimpleXMLElement $country)
    {
        $parameters['data'] = $country;

        return $this->curlRequest(sprintf('%s/Country/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list country.
     *
     * @param SimpleXMLElement $company
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function countryDeleteList(SimpleXMLElement $country)
    {
        $parameters['data'] = $country;

        return $this->curlRequest(sprintf('%s/Country/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get consignment.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Consignment/%s', self::URL, $uuid));
    }

    /**
     * Create consignment.
     *
     * @param SimpleXMLElement $consignment
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentCreate(SimpleXMLElement $consignment)
    {
        $parameters['data'] = $consignment;

        return $this->curlRequest(sprintf('%s/Consignment', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete consignment.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Consignment/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list consignment.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Consignment/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list consignment.
     *
     * @param SimpleXMLElement $consignment
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentUpdateList(SimpleXMLElement $consignment)
    {
        $parameters['data'] = $consignment;

        return $this->curlRequest(sprintf('%s/Consignment/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list consignment.
     *
     * @param SimpleXMLElement $consignment
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function consignmentDeleteList(SimpleXMLElement $consignment)
    {
        $parameters['data'] = $consignment;

        return $this->curlRequest(sprintf('%s/Consignment/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get currency.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Currency/%s', self::URL, $uuid));
    }

    /**
     * Create currency.
     *
     * @param SimpleXMLElement $currency
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyCreate(SimpleXMLElement $currency)
    {
        $parameters['data'] = $currency;

        return $this->curlRequest(sprintf('%s/Currency', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete currency.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Currency/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list currency.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Currency/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list currency.
     *
     * @param SimpleXMLElement $currency
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyUpdateList(SimpleXMLElement $currency)
    {
        $parameters['data'] = $currency;

        return $this->curlRequest(sprintf('%s/Currency/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list currency.
     *
     * @param SimpleXMLElement $currency
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function currencyDeleteList(SimpleXMLElement $currency)
    {
        $parameters['data'] = $currency;

        return $this->curlRequest(sprintf('%s/Currency/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get processing plan folder.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder/%s', self::URL, $uuid));
    }

    /**
     * Create processing plan folder.
     *
     * @param SimpleXMLElement $processingPlanFolder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderCreate(SimpleXMLElement $processingPlanFolder)
    {
        $parameters['data'] = $processingPlanFolder;

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete processing plan folder.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list processing plan folder.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list processing plan folder.
     *
     * @param SimpleXMLElement $processingPlanFolder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderUpdateList(SimpleXMLElement $processingPlanFolder)
    {
        $parameters['data'] = $processingPlanFolder;

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list processing plan folder.
     *
     * @param SimpleXMLElement $processingPlanFolder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanFolderDeleteList(SimpleXMLElement $processingPlanFolder)
    {
        $parameters['data'] = $processingPlanFolder;

        return $this->curlRequest(sprintf('%s/ProcessingPlanFolder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get processing plan.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingPlan/%s', self::URL, $uuid));
    }

    /**
     * Create processing plan.
     *
     * @param SimpleXMLElement $processingPlan
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanCreate(SimpleXMLElement $processingPlan)
    {
        $parameters['data'] = $processingPlan;

        return $this->curlRequest(sprintf('%s/ProcessingPlan', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete processing plan.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingPlan/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list processing plan.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/ProcessingPlan/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list processing plan.
     *
     * @param SimpleXMLElement $processingPlan
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanUpdateList(SimpleXMLElement $processingPlan)
    {
        $parameters['data'] = $processingPlan;

        return $this->curlRequest(sprintf('%s/ProcessingPlan/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list processing plan.
     *
     * @param SimpleXMLElement $processingPlan
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingPlanDeleteList(SimpleXMLElement $processingPlan)
    {
        $parameters['data'] = $processingPlan;

        return $this->curlRequest(sprintf('%s/ProcessingPlan/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get contract.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Contract/%s', self::URL, $uuid));
    }

    /**
     * Create contract.
     *
     * @param SimpleXMLElement $contract
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractCreate(SimpleXMLElement $contract)
    {
        $parameters['data'] = $contract;

        return $this->curlRequest(sprintf('%s/Contract', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete contract.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Contract/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list contract.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Contract/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list contract.
     *
     * @param SimpleXMLElement $contract
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractUpdateList(SimpleXMLElement $contract)
    {
        $parameters['data'] = $contract;

        return $this->curlRequest(sprintf('%s/Contract/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list contract.
     *
     * @param SimpleXMLElement $contract
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function contractDeleteList(SimpleXMLElement $contract)
    {
        $parameters['data'] = $contract;

        return $this->curlRequest(sprintf('%s/Contract/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get project.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Project/%s', self::URL, $uuid));
    }

    /**
     * Create project.
     *
     * @param SimpleXMLElement $project
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectCreate(SimpleXMLElement $project)
    {
        $parameters['data'] = $project;

        return $this->curlRequest(sprintf('%s/Project', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete project.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Project/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list project.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Project/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list project.
     *
     * @param SimpleXMLElement $project
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectUpdateList(SimpleXMLElement $project)
    {
        $parameters['data'] = $project;

        return $this->curlRequest(sprintf('%s/Project/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list project.
     *
     * @param SimpleXMLElement $project
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function projectDeleteList(SimpleXMLElement $project)
    {
        $parameters['data'] = $project;

        return $this->curlRequest(sprintf('%s/Project/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get gtd.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Gtd/%s', self::URL, $uuid));
    }

    /**
     * Create gtd.
     *
     * @param SimpleXMLElement $gtd
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdCreate(SimpleXMLElement $gtd)
    {
        $parameters['data'] = $gtd;

        return $this->curlRequest(sprintf('%s/Gtd', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete gtd.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Gtd/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list gtd.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Gtd/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list gtd.
     *
     * @param SimpleXMLElement $gtd
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdUpdateList(SimpleXMLElement $gtd)
    {
        $parameters['data'] = $gtd;

        return $this->curlRequest(sprintf('%s/Gtd/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list gtd.
     *
     * @param SimpleXMLElement $gtd
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function gtdDeleteList(SimpleXMLElement $gtd)
    {
        $parameters['data'] = $gtd;

        return $this->curlRequest(sprintf('%s/Gtd/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get thing.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Thing/%s', self::URL, $uuid));
    }

    /**
     * Create thing.
     *
     * @param SimpleXMLElement $thing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingCreate(SimpleXMLElement $thing)
    {
        $parameters['data'] = $thing;

        return $this->curlRequest(sprintf('%s/Thing', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete thing.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Thing/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list thing.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Thing/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list thing.
     *
     * @param SimpleXMLElement $thing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingUpdateList(SimpleXMLElement $thing)
    {
        $parameters['data'] = $thing;

        return $this->curlRequest(sprintf('%s/Thing/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list thing.
     *
     * @param SimpleXMLElement $thing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function thingDeleteList(SimpleXMLElement $thing)
    {
        $parameters['data'] = $thing;

        return $this->curlRequest(sprintf('%s/Thing/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get loss reason.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/LossReason/%s', self::URL, $uuid));
    }

    /**
     * Create loss reason.
     *
     * @param SimpleXMLElement $lossReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonCreate(SimpleXMLElement $lossReason)
    {
        $parameters['data'] = $lossReason;

        return $this->curlRequest(sprintf('%s/LossReason', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete loss reason.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/LossReason/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list loss reason.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/LossReason/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list loss reason.
     *
     * @param SimpleXMLElement $lossReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonUpdateList(SimpleXMLElement $lossReason)
    {
        $parameters['data'] = $lossReason;

        return $this->curlRequest(sprintf('%s/LossReason/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list loss reason.
     *
     * @param SimpleXMLElement $lossReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossReasonDeleteList(SimpleXMLElement $lossReason)
    {
        $parameters['data'] = $lossReason;

        return $this->curlRequest(sprintf('%s/LossReason/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get enter reason.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/EnterReason/%s', self::URL, $uuid));
    }

    /**
     * Create enter reason.
     *
     * @param SimpleXMLElement $enterReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonCreate(SimpleXMLElement $enterReason)
    {
        $parameters['data'] = $enterReason;

        return $this->curlRequest(sprintf('%s/EnterReason', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete enter reason.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/EnterReason/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list enter reason.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/EnterReason/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list enter reason.
     *
     * @param SimpleXMLElement $enterReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonUpdateList(SimpleXMLElement $enterReason)
    {
        $parameters['data'] = $enterReason;

        return $this->curlRequest(sprintf('%s/EnterReason/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list enter reason.
     *
     * @param SimpleXMLElement $enterReason
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterReasonDeleteList(SimpleXMLElement $enterReason)
    {
        $parameters['data'] = $enterReason;

        return $this->curlRequest(sprintf('%s/EnterReason/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get custom entity.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CustomEntity/%s', self::URL, $uuid));
    }

    /**
     * Create custom entity.
     *
     * @param SimpleXMLElement $customEntity
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityCreate(SimpleXMLElement $customEntity)
    {
        $parameters['data'] = $customEntity;

        return $this->curlRequest(sprintf('%s/CustomEntity', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete custom entity.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CustomEntity/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list custom entity.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/CustomEntity/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list custom entity.
     *
     * @param SimpleXMLElement $customEntity
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityUpdateList(SimpleXMLElement $customEntity)
    {
        $parameters['data'] = $customEntity;

        return $this->curlRequest(sprintf('%s/CustomEntity/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list custom entity.
     *
     * @param SimpleXMLElement $customEntity
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customEntityDeleteList(SimpleXMLElement $customEntity)
    {
        $parameters['data'] = $customEntity;

        return $this->curlRequest(sprintf('%s/CustomEntity/list/delete', self::URL), self::METHOD_POST, $parameters);
    }
    
    /**
     * Get supply.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyGet($uuid)
    {
        $this->checkUuid($uuid);
    
        return $this->curlRequest(sprintf('%s/Supply/%s', self::URL, $uuid));
    }
    
    /**
     * Create supply.
     *
     * @param SimpleXMLElement $supply
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyCreate(SimpleXMLElement $supply)
    {
        $parameters['data'] = $supply;
    
        return $this->curlRequest(sprintf('%s/Supply', self::URL), self::METHOD_PUT, $parameters);
    }
    
    /**
     * Delete supply.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyDelete($uuid)
    {
        $this->checkUuid($uuid);
    
        return $this->curlRequest(sprintf('%s/Supply/%s', self::URL, $uuid), self::METHOD_DELETE);
    }
    
    /**
     * Get list supply.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;
    
        return $this->curlRequest(sprintf('%s/Supply/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list supply.
     *
     * @param SimpleXMLElement $supply
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyUpdateList(SimpleXMLElement $supply)
    {
        $parameters['data'] = $supply;

        return $this->curlRequest(sprintf('%s/Supply/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list supply.
     *
     * @param SimpleXMLElement $supply
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function supplyDeleteList(SimpleXMLElement $supply)
    {
        $parameters['data'] = $supply;

        return $this->curlRequest(sprintf('%s/Supply/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get demand.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Demand/%s', self::URL, $uuid));
    }

    /**
     * Create demand.
     *
     * @param SimpleXMLElement $demand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandCreate(SimpleXMLElement $demand)
    {
        $parameters['data'] = $demand;

        return $this->curlRequest(sprintf('%s/Demand', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete demand.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Demand/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list demand.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Demand/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list demand.
     *
     * @param SimpleXMLElement $demand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandUpdateList(SimpleXMLElement $demand)
    {
        $parameters['data'] = $demand;

        return $this->curlRequest(sprintf('%s/Demand/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list demand.
     *
     * @param SimpleXMLElement $demand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function demandDeleteList(SimpleXMLElement $demand)
    {
        $parameters['data'] = $demand;

        return $this->curlRequest(sprintf('%s/Demand/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get loss.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Loss/%s', self::URL, $uuid));
    }

    /**
     * Create loss.
     *
     * @param SimpleXMLElement $loss
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossCreate(SimpleXMLElement $loss)
    {
        $parameters['data'] = $loss;

        return $this->curlRequest(sprintf('%s/Loss', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete loss.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Loss/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list loss.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Loss/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list loss.
     *
     * @param SimpleXMLElement $loss
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossUpdateList(SimpleXMLElement $loss)
    {
        $parameters['data'] = $loss;

        return $this->curlRequest(sprintf('%s/Loss/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list loss.
     *
     * @param SimpleXMLElement $loss
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function lossDeleteList(SimpleXMLElement $loss)
    {
        $parameters['data'] = $loss;

        return $this->curlRequest(sprintf('%s/Loss/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get enter.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Enter/%s', self::URL, $uuid));
    }

    /**
     * Create enter.
     *
     * @param SimpleXMLElement $enter
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterCreate(SimpleXMLElement $enter)
    {
        $parameters['data'] = $enter;

        return $this->curlRequest(sprintf('%s/Enter', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete enter.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Enter/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list enter.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Enter/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list enter.
     *
     * @param SimpleXMLElement $enter
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterUpdateList(SimpleXMLElement $enter)
    {
        $parameters['data'] = $enter;

        return $this->curlRequest(sprintf('%s/Enter/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list enter.
     *
     * @param SimpleXMLElement $enter
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function enterDeleteList(SimpleXMLElement $enter)
    {
        $parameters['data'] = $enter;

        return $this->curlRequest(sprintf('%s/Enter/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get sales return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/SalesReturn/%s', self::URL, $uuid));
    }

    /**
     * Create sales return.
     *
     * @param SimpleXMLElement $salesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnCreate(SimpleXMLElement $salesReturn)
    {
        $parameters['data'] = $salesReturn;

        return $this->curlRequest(sprintf('%s/SalesReturn', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete sales return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/SalesReturn/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list sales return.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/SalesReturn/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list sales return.
     *
     * @param SimpleXMLElement $salesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnUpdateList(SimpleXMLElement $salesReturn)
    {
        $parameters['data'] = $salesReturn;

        return $this->curlRequest(sprintf('%s/SalesReturn/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list sales return.
     *
     * @param SimpleXMLElement $salesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function salesReturnDeleteList(SimpleXMLElement $salesReturn)
    {
        $parameters['data'] = $salesReturn;

        return $this->curlRequest(sprintf('%s/SalesReturn/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get purchase return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PurchaseReturn/%s', self::URL, $uuid));
    }

    /**
     * Create purchase return.
     *
     * @param SimpleXMLElement $purchaseReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnCreate(SimpleXMLElement $purchaseReturn)
    {
        $parameters['data'] = $purchaseReturn;

        return $this->curlRequest(sprintf('%s/PurchaseReturn', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete purchase return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PurchaseReturn/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list purchase return.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/PurchaseReturn/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list purchase return.
     *
     * @param SimpleXMLElement $purchaseReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnUpdateList(SimpleXMLElement $purchaseReturn)
    {
        $parameters['data'] = $purchaseReturn;

        return $this->curlRequest(sprintf('%s/PurchaseReturn/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list purchase return.
     *
     * @param SimpleXMLElement $purchaseReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseReturnDeleteList(SimpleXMLElement $purchaseReturn)
    {
        $parameters['data'] = $purchaseReturn;

        return $this->curlRequest(sprintf('%s/PurchaseReturn/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get customer order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CustomerOrder/%s', self::URL, $uuid));
    }

    /**
     * Create customer order.
     *
     * @param SimpleXMLElement $customerOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderCreate(SimpleXMLElement $customerOrder)
    {
        $parameters['data'] = $customerOrder;

        return $this->curlRequest(sprintf('%s/CustomerOrder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete customer order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CustomerOrder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list customer order.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/CustomerOrder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list customer order.
     *
     * @param SimpleXMLElement $customerOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderUpdateList(SimpleXMLElement $customerOrder)
    {
        $parameters['data'] = $customerOrder;

        return $this->curlRequest(sprintf('%s/CustomerOrder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list customer order.
     *
     * @param SimpleXMLElement $customerOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function customerOrderDeleteList(SimpleXMLElement $customerOrder)
    {
        $parameters['data'] = $customerOrder;

        return $this->curlRequest(sprintf('%s/CustomerOrder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get purchase order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PurchaseOrder/%s', self::URL, $uuid));
    }

    /**
     * Create purchase order.
     *
     * @param SimpleXMLElement $purchaseOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderCreate(SimpleXMLElement $purchaseOrder)
    {
        $parameters['data'] = $purchaseOrder;

        return $this->curlRequest(sprintf('%s/PurchaseOrder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete purchase order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PurchaseOrder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list purchase order.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/PurchaseOrder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list purchase order.
     *
     * @param SimpleXMLElement $purchaseOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderUpdateList(SimpleXMLElement $purchaseOrder)
    {
        $parameters['data'] = $purchaseOrder;

        return $this->curlRequest(sprintf('%s/PurchaseOrder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list purchase order.
     *
     * @param SimpleXMLElement $purchaseOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function purchaseOrderDeleteList(SimpleXMLElement $purchaseOrder)
    {
        $parameters['data'] = $purchaseOrder;

        return $this->curlRequest(sprintf('%s/PurchaseOrder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get internal order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/InternalOrder/%s', self::URL, $uuid));
    }

    /**
     * Create internal order.
     *
     * @param SimpleXMLElement $internalOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderCreate(SimpleXMLElement $internalOrder)
    {
        $parameters['data'] = $internalOrder;

        return $this->curlRequest(sprintf('%s/InternalOrder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete internal order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/InternalOrder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list internal order.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/InternalOrder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list internal order.
     *
     * @param SimpleXMLElement $internalOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderUpdateList(SimpleXMLElement $internalOrder)
    {
        $parameters['data'] = $internalOrder;

        return $this->curlRequest(sprintf('%s/InternalOrder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list internal order.
     *
     * @param SimpleXMLElement $internalOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function internalOrderDeleteList(SimpleXMLElement $internalOrder)
    {
        $parameters['data'] = $internalOrder;

        return $this->curlRequest(sprintf('%s/InternalOrder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get processing order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingOrder/%s', self::URL, $uuid));
    }

    /**
     * Create processing order.
     *
     * @param SimpleXMLElement $processingOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderCreate(SimpleXMLElement $processingOrder)
    {
        $parameters['data'] = $processingOrder;

        return $this->curlRequest(sprintf('%s/ProcessingOrder', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete processing order.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/ProcessingOrder/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list processing order.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/ProcessingOrder/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list processing order.
     *
     * @param SimpleXMLElement $processingOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderUpdateList(SimpleXMLElement $processingOrder)
    {
        $parameters['data'] = $processingOrder;

        return $this->curlRequest(sprintf('%s/ProcessingOrder/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list processing order.
     *
     * @param SimpleXMLElement $processingOrder
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingOrderDeleteList(SimpleXMLElement $processingOrder)
    {
        $parameters['data'] = $processingOrder;

        return $this->curlRequest(sprintf('%s/ProcessingOrder/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get processing.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Processing/%s', self::URL, $uuid));
    }

    /**
     * Create processing.
     *
     * @param SimpleXMLElement $processing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingCreate(SimpleXMLElement $processing)
    {
        $parameters['data'] = $processing;

        return $this->curlRequest(sprintf('%s/Processing', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete processing.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Processing/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list processing.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Processing/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list processing.
     *
     * @param SimpleXMLElement $processing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingUpdateList(SimpleXMLElement $processing)
    {
        $parameters['data'] = $processing;

        return $this->curlRequest(sprintf('%s/Processing/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list processing.
     *
     * @param SimpleXMLElement $processing
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function processingDeleteList(SimpleXMLElement $processing)
    {
        $parameters['data'] = $processing;

        return $this->curlRequest(sprintf('%s/Processing/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get move.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Move/%s', self::URL, $uuid));
    }

    /**
     * Create move.
     *
     * @param SimpleXMLElement $move
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveCreate(SimpleXMLElement $move)
    {
        $parameters['data'] = $move;

        return $this->curlRequest(sprintf('%s/Move', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete move.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Move/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list move.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Move/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list move.
     *
     * @param SimpleXMLElement $move
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveUpdateList(SimpleXMLElement $move)
    {
        $parameters['data'] = $move;

        return $this->curlRequest(sprintf('%s/Move/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list move.
     *
     * @param SimpleXMLElement $move
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function moveDeleteList(SimpleXMLElement $move)
    {
        $parameters['data'] = $move;

        return $this->curlRequest(sprintf('%s/Move/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get inventory.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Inventory/%s', self::URL, $uuid));
    }

    /**
     * Create inventory.
     *
     * @param SimpleXMLElement $inventory
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryCreate(SimpleXMLElement $inventory)
    {
        $parameters['data'] = $inventory;

        return $this->curlRequest(sprintf('%s/Inventory', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete inventory.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Inventory/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list inventory.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Inventory/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list inventory.
     *
     * @param SimpleXMLElement $inventory
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryUpdateList(SimpleXMLElement $inventory)
    {
        $parameters['data'] = $inventory;

        return $this->curlRequest(sprintf('%s/Inventory/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list inventory.
     *
     * @param SimpleXMLElement $inventory
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function inventoryDeleteList(SimpleXMLElement $inventory)
    {
        $parameters['data'] = $inventory;

        return $this->curlRequest(sprintf('%s/Inventory/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get cash in.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CashIn/%s', self::URL, $uuid));
    }

    /**
     * Create cash in.
     *
     * @param SimpleXMLElement $cashIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInCreate(SimpleXMLElement $cashIn)
    {
        $parameters['data'] = $cashIn;

        return $this->curlRequest(sprintf('%s/CashIn', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete cash in.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CashIn/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list cash in.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/CashIn/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list cash in.
     *
     * @param SimpleXMLElement $cashIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInUpdateList(SimpleXMLElement $cashIn)
    {
        $parameters['data'] = $cashIn;

        return $this->curlRequest(sprintf('%s/CashIn/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list cash in.
     *
     * @param SimpleXMLElement $cashIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashInDeleteList(SimpleXMLElement $cashIn)
    {
        $parameters['data'] = $cashIn;

        return $this->curlRequest(sprintf('%s/CashIn/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get cash out.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CashOut/%s', self::URL, $uuid));
    }

    /**
     * Create cash out.
     *
     * @param SimpleXMLElement $cashOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutCreate(SimpleXMLElement $cashOut)
    {
        $parameters['data'] = $cashOut;

        return $this->curlRequest(sprintf('%s/CashOut', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete cash out.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/CashOut/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list cash out.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/CashOut/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list cash out.
     *
     * @param SimpleXMLElement $cashOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutUpdateList(SimpleXMLElement $cashOut)
    {
        $parameters['data'] = $cashOut;

        return $this->curlRequest(sprintf('%s/CashOut/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list cash out.
     *
     * @param SimpleXMLElement $cashOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function cashOutDeleteList(SimpleXMLElement $cashOut)
    {
        $parameters['data'] = $cashOut;

        return $this->curlRequest(sprintf('%s/CashOut/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get payment in.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PaymentIn/%s', self::URL, $uuid));
    }

    /**
     * Create payment in.
     *
     * @param SimpleXMLElement $paymentIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInCreate(SimpleXMLElement $paymentIn)
    {
        $parameters['data'] = $paymentIn;

        return $this->curlRequest(sprintf('%s/PaymentIn', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete payment in.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PaymentIn/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list payment in.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/PaymentIn/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list payment in.
     *
     * @param SimpleXMLElement $paymentIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInUpdateList(SimpleXMLElement $paymentIn)
    {
        $parameters['data'] = $paymentIn;

        return $this->curlRequest(sprintf('%s/PaymentIn/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list payment in.
     *
     * @param SimpleXMLElement $paymentIn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentInDeleteList(SimpleXMLElement $paymentIn)
    {
        $parameters['data'] = $paymentIn;

        return $this->curlRequest(sprintf('%s/PaymentIn/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get payment out.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PaymentOut/%s', self::URL, $uuid));
    }

    /**
     * Create payment out.
     *
     * @param SimpleXMLElement $paymentOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutCreate(SimpleXMLElement $paymentOut)
    {
        $parameters['data'] = $paymentOut;

        return $this->curlRequest(sprintf('%s/PaymentOut', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete payment out.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/PaymentOut/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list payment out.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/PaymentOut/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list payment out.
     *
     * @param SimpleXMLElement $paymentOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutUpdateList(SimpleXMLElement $paymentOut)
    {
        $parameters['data'] = $paymentOut;

        return $this->curlRequest(sprintf('%s/PaymentOut/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list payment out.
     *
     * @param SimpleXMLElement $paymentOut
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function paymentOutDeleteList(SimpleXMLElement $paymentOut)
    {
        $parameters['data'] = $paymentOut;

        return $this->curlRequest(sprintf('%s/PaymentOut/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get retail demand.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/RetailDemand/%s', self::URL, $uuid));
    }

    /**
     * Create retail demand.
     *
     * @param SimpleXMLElement $retailDemand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandCreate(SimpleXMLElement $retailDemand)
    {
        $parameters['data'] = $retailDemand;

        return $this->curlRequest(sprintf('%s/RetailDemand', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete retail demand.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/RetailDemand/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list retail demand.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/RetailDemand/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list retail demand.
     *
     * @param SimpleXMLElement $retailDemand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandUpdateList(SimpleXMLElement $retailDemand)
    {
        $parameters['data'] = $retailDemand;

        return $this->curlRequest(sprintf('%s/RetailDemand/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list retail demand.
     *
     * @param SimpleXMLElement $retailDemand
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailDemandDeleteList(SimpleXMLElement $retailDemand)
    {
        $parameters['data'] = $retailDemand;

        return $this->curlRequest(sprintf('%s/RetailDemand/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get retail sales return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/RetailSalesReturn/%s', self::URL, $uuid));
    }

    /**
     * Create retail sales return.
     *
     * @param SimpleXMLElement $retailSalesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnCreate(SimpleXMLElement $retailSalesReturn)
    {
        $parameters['data'] = $retailSalesReturn;

        return $this->curlRequest(sprintf('%s/RetailSalesReturn', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete retail sales return.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/RetailSalesReturn/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list retail sales return.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/RetailSalesReturn/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list retail sales return.
     *
     * @param SimpleXMLElement $retailSalesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnUpdateList(SimpleXMLElement $retailSalesReturn)
    {
        $parameters['data'] = $retailSalesReturn;

        return $this->curlRequest(sprintf('%s/RetailSalesReturn/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list retail sales return.
     *
     * @param SimpleXMLElement $retailSalesReturn
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function retailSalesReturnDeleteList(SimpleXMLElement $retailSalesReturn)
    {
        $parameters['data'] = $retailSalesReturn;

        return $this->curlRequest(sprintf('%s/RetailSalesReturn/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Get workflow.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowGet($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Workflow/%s', self::URL, $uuid));
    }

    /**
     * Create workflow.
     *
     * @param SimpleXMLElement $workflow
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowCreate(SimpleXMLElement $workflow)
    {
        $parameters['data'] = $workflow;

        return $this->curlRequest(sprintf('%s/Workflow', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete workflow.
     *
     * @param string $uuid
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowDelete($uuid)
    {
        $this->checkUuid($uuid);

        return $this->curlRequest(sprintf('%s/Workflow/%s', self::URL, $uuid), self::METHOD_DELETE);
    }

    /**
     * Get list workflow.
     *
     * @param array $filter
     * @param integer $start
     * @param integer $count
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowGetList($filter = array(), $start = 0, $count = 1000)
    {
        $parameters['filter'] = $filter;
        $parameters['start'] = $start;
        $parameters['count'] = $count;

        return $this->curlRequest(sprintf('%s/Workflow/list', self::URL), self::METHOD_GET, $parameters);
    }

    /**
     * Update list workflow.
     *
     * @param SimpleXMLElement $workflow
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowUpdateList(SimpleXMLElement $workflow)
    {
        $parameters['data'] = $workflow;

        return $this->curlRequest(sprintf('%s/Workflow/list/update', self::URL), self::METHOD_PUT, $parameters);
    }

    /**
     * Delete list workflow.
     *
     * @param SimpleXMLElement $workflow
     * @return SimpleXMLElement
     * @access public
     * @final
     */
    final public function workflowDeleteList(SimpleXMLElement $workflow)
    {
        $parameters['data'] = $workflow;

        return $this->curlRequest(sprintf('%s/Workflow/list/delete', self::URL), self::METHOD_POST, $parameters);
    }

    /**
     * Execution of the request
     * 
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @param integer $timeout
     * @return mixed
     * @throws CurlException
     * @throws MSException
     * @access protected
     */
    protected function curlRequest($url, $method = 'GET', $parameters = null)
    {
        set_time_limit(0);

        if (!is_null($parameters) && $method == self::METHOD_GET) {
            $url .= $this->httpBuildQuery($parameters);
        }

        if (!$this->curl) {
            $this->curl = curl_init();
        }

        //Set general arguments
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'MS-API-client/1.1');
        curl_setopt($this->curl, CURLOPT_USERPWD, "{$this->login}:{$this->password}");
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_FAILONERROR, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array());
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());

        if ($method == self::METHOD_POST) {
            curl_setopt($this->curl, CURLOPT_POST, true);
        } elseif (in_array($method, array(self::METHOD_PUT, self::METHOD_DELETE))) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if (
            !is_null($parameters) &&
            in_array($method, array(self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE)) &&
            isset($parameters['data'])
        ) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/xml',
                'Accept: */*'
            ));
            $data = html_entity_decode($parameters['data']->asXML());
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($this->curl);
        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $errno = curl_errno($this->curl);
        $error = curl_error($this->curl);

        if ($errno) {
            throw new CurlException($error, $errno);
        }

        $result = $this->getResult($response);

        if ($statusCode >= 400) {
            throw new MSException($this->getError($result), $statusCode);
        }

        return $result;
    }

    /**
     * Gets the query result.
     *
     * @param string $response
     * @return SimpleXMLElement|DOMDocument
     * @access private
     */
    private function getResult($response)
    {
        libxml_use_internal_errors(true);
        $result = simplexml_load_string($response);

        if (!$result) {
            $document = new DOMDocument();
            $document->loadHTML($response);
            $result = $this->clearDomDocument($document);
        }

        return $result;
    }

    /**
     * Get error.
     * 
     * @param SimpleXMLElement|DOMDocument $result
     * @return string
     * @access private
     */
    private function getError($result)
    {
        $error = 'Internal error.';
        if ($result instanceof DOMDocument) {
            $error = strip_tags($result->textContent);
        } elseif (count($result->message) > 0) {
            $error = '';
            foreach ($result->message as $message) {
                $error .= "{$message}\n";
            }
        }

        return $error;
    }

    /**
     * Http build query.
     *
     * @param array $parameters
     * @return string
     * @access private
     */
    private function httpBuildQuery($parameters)
    {
        if (isset($parameters['filter']) && is_array($parameters['filter'])) {
            $filter = '';
            foreach ($parameters['filter'] as $name => $value) {
                $filter .= "{$name}{$value};";
            }

            $parameters['filter'] = trim($filter, ';');
        }

        return '?' . http_build_query($parameters);
    }

    /**
     * It clears the document from the trash.
     *
     * @param DOMDocument $document
     * @return DOMDocument
     * @access private
     */
    private function clearDomDocument(DOMDocument $document)
    {
        $tags = array('head', 'h1', 'h3');

        foreach ($tags as $tag) {
            $element = $document->getElementsByTagName($tag);
            if ($element->length > 0) {
                $element->item(0)->parentNode->removeChild($element->item(0));
            }
        }

        return $document;
    }

    /**
     * Check uuid.
     * 
     * @param string $uuid
     * @throws InvalidArgumentException
     * @access private
     */
    private function checkUuid($uuid)
    {
        if (is_null($uuid) || empty($uuid)) {
            throw new InvalidArgumentException('The `uuid` can not be empty');
        }
    }

    /**
     * Do some actions when instance destroyed
     * @access public
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @return integer
     * @access public
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param integer $timeout
     * @return MSRestApi
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }
 
}

/**
 * Exception for CURL
 * @author Dmitry Mamontov <d.slonyara@gmail.com>
 */
class CurlException extends RuntimeException
{
}

/**
 * Exception for Moy Sklad
 * @author Dmitry Mamontov <d.slonyara@gmail.com>
 */
class MSException extends RuntimeException
{
}