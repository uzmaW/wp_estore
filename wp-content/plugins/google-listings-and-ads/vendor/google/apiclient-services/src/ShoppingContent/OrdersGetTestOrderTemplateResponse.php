<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Service\ShoppingContent;

class OrdersGetTestOrderTemplateResponse extends \Automattic\WooCommerce\GoogleListingsAndAds\Vendor\Google\Model
{
  /**
   * @var string
   */
  public $kind;
  /**
   * @var TestOrder
   */
  public $template;
  protected $templateType = TestOrder::class;
  protected $templateDataType = '';

  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param TestOrder
   */
  public function setTemplate(TestOrder $template)
  {
    $this->template = $template;
  }
  /**
   * @return TestOrder
   */
  public function getTemplate()
  {
    return $this->template;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrdersGetTestOrderTemplateResponse::class, 'Google_Service_ShoppingContent_OrdersGetTestOrderTemplateResponse');
