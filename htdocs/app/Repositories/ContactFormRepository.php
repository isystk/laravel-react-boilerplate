<?php

namespace App\Repositories;

use App\Constants\ErrorType;
use Illuminate\Support\Facades\DB;
use App\Models\ContactForm;

class ContactFormRepository
{

  public function count($createdAt, $options = [])
  {
      $query = ContactForm::whereDay([
          'created_at' => $createdAt,
      ]);
      return $query->count();
  }

  public function all($createdAt, $options = [])
  {
      return ContactForm::with($this->__with($options))
          ->whereDay([
            'created_at' => $createdAt,
          ])
          ->get();
  }

  public function find($id, $options = [])
  {
      return ContactForm::with($this->__with($options))
          ->find($id);
  }

  private function __with($options = [])
  {
      $with = [];
      if (!empty($options['with:images'])) {
          $with[] = 'contactFormImages';
      }
      return $with;
  }

  public function store(
      $id,
      $yourName,
      $title,
      $email,
      $url,
      $gender,
      $age,
      $contact
  ) {
      DB::beginTransaction();
      try {
          $contactForm = new ContactForm();
          $contactForm->id = $id;
          $contactForm->yourName = $yourName;
          $contactForm->title = $title;
          $contactForm->email = $email;
          $contactForm->url = $url;
          $contactForm->gender = $gender;
          $contactForm->age = $age;
          $contactForm->contact = $contact;

          $contactForm->save();

          DB::commit();
          return [$contactForm, ErrorType::SUCCESS, null];
      } catch (\PDOException $e) {
          DB::rollBack();
          return [false, ErrorType::DATABASE, $e];
      } catch (\Exception $e) {
          DB::rollBack();
          return [false, ErrorType::FATAL, $e];
      }
  }

  public function update(
    $id,
    $yourName,
    $title,
    $email,
    $url,
    $gender,
    $age,
    $contact
  ) {
      DB::beginTransaction();
      try {
          $contactForm = $this->find($id);
          $contactForm->yourName = $yourName;
          $contactForm->title = $title;
          $contactForm->email = $email;
          $contactForm->url = $url;
          $contactForm->gender = $gender;
          $contactForm->age = $age;
          $contactForm->contact = $contact;
          $contactForm->save();

          DB::commit();
          return [$contactForm, ErrorType::SUCCESS, null];
      } catch (\PDOException $e) {
          DB::rollBack();
          return [false, ErrorType::DATABASE, $e];
      } catch (\Exception $e) {
          DB::rollBack();
          return [false, ErrorType::FATAL, $e];
      }
  }

}
