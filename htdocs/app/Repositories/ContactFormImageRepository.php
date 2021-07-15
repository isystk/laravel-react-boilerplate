<?php

namespace App\Repositories;

use App\Models\ContactFormImage;

class ContactFormImageRepository
{

  public function count($createdAt, $options = [])
  {
      $query = ContactFormImage::whereDay([
          'created_at' => $createdAt,
      ]);
      return $query->count();
  }

  public function findAll($contactFormId, $options = [])
  {
      $query = ContactFormImage::with($this->__with($options))
        ->where([
          'contact_form_id' => $contactFormId
        ]);

      if (!empty($options['paging'])) {
        return $query
            ->paginate($options['paging']);
      } else {
        return $query
          ->get();
      }
  }

  public function findById($id, $options = [])
  {
      return ContactFormImage::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
  }

  private function __with($options = [])
  {
      $with = [];
      return $with;
  }

  public function store(
      $id,
      $contactFormId,
      $fileName
  ) {
      $contactFormImage = new ContactFormImage();
      $contactFormImage->id = $id;
      $contactFormImage->contact_form_id = $contactFormId;
      $contactFormImage->file_name = $fileName;

      $contactFormImage->save();

      return $contactFormImage;
  }

  public function update(
    $id,
    $contactFormId,
    $fileName
  ) {
      $contactFormImage = $this->findById($id);
      $contactFormImage->contact_form_id = $contactFormId;
      $contactFormImage->file_name = $fileName;
      $contactFormImage->save();

      return $contactFormImage;
  }

  public function delete(
    $id
  ) {
      $contactFormImage = $this->findById($id);
      $contactFormImage->delete();

      return $contactFormImage;
  }

}
