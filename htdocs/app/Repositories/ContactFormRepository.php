<?php

namespace App\Repositories;

use App\Models\ContactFormImage;
use Illuminate\Support\Facades\DB;
use App\Services\UploadImage;
use App\Models\ContactForm;

class ContactFormRepository
{

  public function count($dbShiftConditionId, $options = [])
  {
      $query = DBPairConstraint::where([
          'd_b_shift_condition_id' => $dbShiftConditionId,
      ]);
      if (!empty($options['only:enabled'])) {
          $query->where('status', DBPairConstraint::ACTIVATED);
      }
      return $query->count();
  }

  public function all($dbShiftConditionId, $options = [])
  {
      return DBPairConstraint::sortable(['createdAt' => 'asc'])
          ->with($this->__with($options))
          ->where([
              'd_b_shift_condition_id' => $dbShiftConditionId,
          ])
          ->get();
  }

  public function find($branchId, $id, $options = [])
  {
      return DBPairConstraint::with($this->__with($options))
          ->branch($branchId)
          ->find($id);
  }

  private function __with($options = [])
  {
      $with = [];
      if (!empty($options['with:condition'])) {
          $with[] = 'condition';
      }
      if (!empty($options['with:objects'])) {
          $with[] = 'dateObject';
          $with[] = 'masterStaffObject';
          $with[] = 'masterShiftObject';
          $with[] = 'servantStaffObject';
          $with[] = 'servantShiftObject';
      }
      return $with;
  }

  public function store(
      $conditionId,
      $variables,
      $dateObject,
      $masterStaffObject,
      $masterShiftObject,
      $servantStaffObject,
      $servantShiftObject,
      $savedBy
  ) {
      DB::beginTransaction();
      try {
          $constraint = new DBPairConstraint();
          $constraint->d_b_shift_condition_id = $conditionId;
          $constraint->name = '';
          foreach ($variables as $key => $value) {
              $constraint->{$key} = $value;
          }

          if (!empty($dateObject) && !empty($dateObject['id'])) {
              $constraint->date_object_id = $dateObject['id'];
              $constraint->date_object_type = $dateObject['type'];
          }

          if (!empty($masterShiftObject) && !empty($masterShiftObject['id'])) {
              $constraint->master_shift_object_id = $masterShiftObject['id'];
              $constraint->master_shift_object_type = $masterShiftObject['type'];
              $constraint->master_shift_object_inverse = !empty($masterShiftObject['inverse']);
          }
          if (!empty($masterStaffObject) && !empty($masterStaffObject['id'])) {
              $constraint->master_staff_object_id = $masterStaffObject['id'];
              $constraint->master_staff_object_type = $masterStaffObject['type'];
              $constraint->master_staff_operator = $masterStaffObject['operator'];
          }

          if (!empty($servantShiftObject) && !empty($servantShiftObject['id'])) {
              $constraint->servant_shift_object_id = $servantShiftObject['id'];
              $constraint->servant_shift_object_type = $servantShiftObject['type'];
              $constraint->servant_shift_object_inverse = !empty($servantShiftObject['inverse']);
          }
          if (!empty($servantStaffObject) && !empty($servantStaffObject['id'])) {
              $constraint->servant_staff_object_id = $servantStaffObject['id'];
              $constraint->servant_staff_object_type = $servantStaffObject['type'];
              $constraint->servant_staff_operator = $servantStaffObject['operator'];
          }

          $constraint->status = DBPairConstraint::ACTIVATED;
          $constraint->created_by = $savedBy;
          $constraint->save();

          DB::commit();
          return [$constraint, ErrorType::SUCCESS, null];
      } catch (\PDOException $e) {
          DB::rollBack();
          return [false, ErrorType::DATABASE, $e];
      } catch (\Exception $e) {
          DB::rollBack();
          return [false, ErrorType::FATAL, $e];
      }
  }

  public function update(
      $branchId,
      $constraintId,
      $variables,
      $dateObject,
      $masterStaffObject,
      $masterShiftObject,
      $servantStaffObject,
      $servantShiftObject,
      $savedBy
  ) {
      DB::beginTransaction();
      try {
          $constraint = $this->find($branchId, $constraintId);
          foreach ($variables as $key => $value) {
              $constraint->{$key} = $value;
          }

          if (!empty($dateObject) && !empty($dateObject['id'])) {
              $constraint->date_object_id = $dateObject['id'];
              $constraint->date_object_type = $dateObject['type'];
          }

          if (!empty($masterShiftObject) && !empty($masterShiftObject['id'])) {
              $constraint->master_shift_object_id = $masterShiftObject['id'];
              $constraint->master_shift_object_type = $masterShiftObject['type'];
              $constraint->master_shift_object_inverse = !empty($masterShiftObject['inverse']);
          }
          if (!empty($masterStaffObject) && !empty($masterStaffObject['id'])) {
              $constraint->master_staff_object_id = $masterStaffObject['id'];
              $constraint->master_staff_object_type = $masterStaffObject['type'];
              $constraint->master_staff_operator = $masterStaffObject['operator'];
          }

          if (!empty($servantShiftObject) && !empty($servantShiftObject['id'])) {
              $constraint->servant_shift_object_id = $servantShiftObject['id'];
              $constraint->servant_shift_object_type = $servantShiftObject['type'];
              $constraint->servant_shift_object_inverse = !empty($servantShiftObject['inverse']);
          }
          if (!empty($servantStaffObject) && !empty($servantStaffObject['id'])) {
              $constraint->servant_staff_object_id = $servantStaffObject['id'];
              $constraint->servant_staff_object_type = $servantStaffObject['type'];
              $constraint->servant_staff_operator = $servantStaffObject['operator'];
          }

          $constraint->updated_by = $savedBy;
          $constraint->save();

          DB::commit();
          return [$constraint, ErrorType::SUCCESS, null];
      } catch (\PDOException $e) {
          DB::rollBack();
          return [false, ErrorType::DATABASE, $e];
      } catch (\Exception $e) {
          DB::rollBack();
          return [false, ErrorType::FATAL, $e];
      }
  }

}
