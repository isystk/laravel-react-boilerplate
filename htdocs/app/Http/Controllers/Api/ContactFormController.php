<?php

namespace App\Http\Controllers\Api;

use App\Enums\ErrorType;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreContactFormRequest;

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
  public function store(StoreContactFormRequest $request)
  {
      [$contactForm, $type, $exception] = $this->contactFormService->save();
      if (!$contactForm) {
          if ($type === ErrorType::NOT_FOUND) {
              abort(400);
          }
          $e = $exception ?? new \Exception(__('common.Unknown Error has occurred.'));
          $result = [
            'result' => false,
            'error' => [
              'messages' => [$e->getMessage()]
            ],
          ];
          return $this->resConversionJson($result, $e->getCode());
      }
      return $this->resConversionJson([
        'result'      => true,
      ]);
  }
}
