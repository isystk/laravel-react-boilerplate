<?php

namespace App\Repositories;

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

  public function findAll($yourName, $options = [])
  {
      $query = ContactForm::with($this->__with($options))
        ->orderBy('created_at', 'desc')
        ->orderBy('id', 'asc');

      // もしキーワードがあったら
      if ($yourName !== null) {
        // 全角スペースを半角に
        $search_split = mb_convert_kana($yourName, 's');

        // 空白で区切る
        $search_split2 = preg_split('/[\s]+/', $search_split);

        // 単語をループで回す
        foreach ($search_split2 as $value) {
          $query->where('your_name', 'like', '%' . $value . '%');
        }
      }

      $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
      return $limit > 0 ? $query->paginate($limit) : $query->get();
  }

  public function findById($id, $options = [])
  {
      return ContactForm::with($this->__with($options))
          ->where([
              'id' => $id
          ])
          ->first();
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
      $contactForm = new ContactForm();
      $contactForm->id = $id;
      $contactForm->your_name = $yourName;
      $contactForm->title = $title;
      $contactForm->email = $email;
      $contactForm->url = $url;
      $contactForm->gender = $gender;
      $contactForm->age = $age;
      $contactForm->contact = $contact;

      $contactForm->save();

      return $contactForm;
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
      $contactForm = $this->findById($id);
      $contactForm->your_name = $yourName;
      $contactForm->title = $title;
      $contactForm->email = $email;
      $contactForm->url = $url;
      $contactForm->gender = $gender;
      $contactForm->age = $age;
      $contactForm->contact = $contact;
      $contactForm->save();

      return $contactForm;
  }

  public function delete(
    $id
  ) {
      $contactForm = $this->findById($id);
      $contactForm->delete();

      return $contactForm;
  }

}
