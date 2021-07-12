<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreContactForm;

use App\Services\ContactFormService;

class ContactFormController extends ApiController
{
  /**
   * @var ContactFormService
   */
  protected $contactFormService;

  public function __construct(ContactFormService $contactFormService)
  {
      $this->contactFormService = $contactFormService;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreContactForm $request)
  {
    try {

      $this->contactFormService->createContactForm($request);

      $result = [
        'result'      => true,
      ];
    } catch (\Exception $e) {
      $result = [
        'result' => false,
        'error' => [
          'messages' => [$e->getMessage()]
        ],
      ];
      return $this->resConversionJson($result, $e->getCode());
    }
    return $this->resConversionJson($result);
  }
}
