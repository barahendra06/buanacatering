<?php namespace App\Traits;
use Validator;

trait EventTrait 
{
	protected function validator(array $member, $event)
    {
        // validate member 
        $gender = array();
        $event->male ? array_push($gender, 'male'):'';
        $event->female ? array_push($gender, 'female'):'';
        
        // educations array
        $educations = array();
        foreach($event->educationType as $educationType)
        {
            array_push($educations, $educationType->id);
        }

        // provinces array 
        $provinces = array();
        foreach($event->province as $province)
        {
            array_push($provinces, $province->id);
        }

		$messages = [
					    'age.between' => 'age('.$member['age'].')',
					    'education_type_id.in' => $member['education_type_id'] ? 'education('.EDUCATION_TYPE_ARRAY[$member['education_type_id']].')' : "education(Not set)",
					    'province_id.in' => $member['province_name'] ? 'province('.$member['province_name'].')' : "province(Not set)",
					    'gender.in' => 'gender('.$member['gender'].')',
					];

        $validator = Validator::make($member, [
            'age' => 'integer|between:'.$event->min_age.','.$event->max_age,
            'gender' => 'string|in:'.implode(",", $gender),
            'education_type_id' => 'in:'.implode(",", $educations),   
            'province_id' => 'in:'.implode(",", $provinces),    
        ], $messages);

        if ($validator->fails()) 
        {
			$canRegister = false;
        }
        else
        {
        	$canRegister = true;	
        }
        return ['canRegister'=>$canRegister, 'errors'=>$validator->errors()->all()];
    }
	
}
