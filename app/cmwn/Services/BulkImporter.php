<?php

namespace app\cmwn\Services;

use app\Group;
use Illuminate\Foundation\Bus\DispatchesJobs;
use app\District;
use app\Organization;
use app\User;
use Illuminate\Support\Facades\Auth;

class BulkImporter
{
    use DispatchesJobs;

    public static $data;

    public static function migratecsv()
    {
        $file = base_path('storage/app/yourcsvfile.csv');
        $csv = self::csv_to_array($file);
        return self::updateDB($csv);
    }

    public static function migrateTeachers(){
        $file = base_path('storage/app/yourcsvfile.csv');
        $csv = self::csv_to_array($file);
        return self::updateTeachers($csv);
    }

    public static function migrateClasses(){
        $file = base_path('storage/app/yourcsvfile.csv');
        $csv = self::csv_to_array($file);
        return self::updateClasses($csv);
    }

    public static function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    protected static function updateDB($data)
    {
        foreach ($data as $title) {
            if ($title['STUDENT ID'] != '') {

                //creating or updating districts
                $DDBNNN = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $title['DDBNNN']);

                //Adding Districts
                $district = District::firstOrCreate(['code' => $DDBNNN[0], 'system_id' => 1]);
                $district->code = $DDBNNN[0];
                $district->system_id = 1;
                $district->title = 'District '.$DDBNNN[0];
                $district->save();

                //Adding Organizations
                $organization = Organization::where(['code' => $DDBNNN[1]])
                                ->with(array('districts' => function ($query) use ($district) {
                                                                $query->where('district_id', $district->id);
                                                            }))->first();

                if (is_null($organization)) { // TODO figure out if this can be replaced with a firstOrCreate;
                    $organization = new Organization();
                }

                $organization->code = $DDBNNN[1];
                $organization->title = $DDBNNN[1];
                $organization->save();

                if (!$organization->districts->contains($district->id)) {
                    $organization->districts()->attach($district->id);
                }

                //Adding groups
                $group = Group::firstOrCreate(['organization_id' => $organization->id]);
                $group->title = $title['OFF CLS'];
                $group->save();

                //Adding students
                $user = User::firstOrCreate(['student_id' => $title['STUDENT ID']]);
                $user->student_id = $title['STUDENT ID'];
                $user->first_name = $title['FIRST NAME'];
                $user->last_name = $title['LAST NAME'];
                $user->sex = $title['SEX'];
                $user->dob = $title['BIRTH DT'];
                $user->save();
                $child_id = $user->id;

                //Adding guardians
//                if ($title['EMAIL']!='') {
//                    $guardian = User::firstOrCreate(['email' => $title['EMAIL']]);
//                    $guardian->student_id = $title['EMAIL'];
//                    $guardian->first_name = $title['FIRST NAME'] . '\'s ' . $title['ADULT FIRST 1'];
//                    $guardian->last_name = $title['ADULT LAST 1'];
//                    $guardian->save();
//                    $guardian->children()->sync( array(
//                        $guardian->id => $child_id,
//                    ));
//                }

                $guardian = \DB::table('guardian_validation')
                    ->where('student_id','=', $title['STUDENT ID'])
                    ->where('first_name','=', $title['ADULT FIRST 1'])
                    ->where('last_name','=', $title['ADULT LAST 1'])
                    ->get();

               if (isset($guardian[0]->id)){
                   $output = \DB::table('guardian_validation')->where('student_id', $guardian[0]->student_id)
                       ->update(array(
                           'student_id' => $title['STUDENT ID'],
                           'first_name' => $title['ADULT FIRST 1'],
                           'last_name' => $title['ADULT LAST 1'],
                           'phone' => $title['ADULT PHONE 1'],
                       ));
               }else{
                    $output = \DB::table('guardian_validation')->insert(array(
                        'student_id' => $title['STUDENT ID'],
                        'first_name' => $title['ADULT FIRST 1'],
                        'last_name' => $title['ADULT LAST 1'],
                        'phone' => $title['ADULT PHONE 1'],
                   ));
               }

            }
        }

        $notifier = new Notifier();
        $notifier->to = Auth::user()->email;
        $notifier->subject = 'Your import is completed at '.date('m-d-Y h:i:s A');
        $notifier->template = 'emails.import';
        $notifier->attachData(['user' => Auth::user()]);
        $notifier->send();
    }



    protected static function updateTeachers($data){

        foreach ($data as $title) {
            $id = $title['Person Type'].' '.$title['First Name']." ".$title['Last Name'];
            $id = str_slug($id);
            $techers = User::firstOrCreate(['student_id' => $id]);
            $techers->student_id = $id;
            $techers->first_name = $title['First Name'];
            $techers->last_name = $title['Last Name'];
            $techers->sex = $title['Gender'];
            $saved = $techers->save();
            $teacher_id = $techers->id;
            echo $teacher_id."<br />";
            $role_id = 0;
            switch($title['Person Type']){
                case 'Principal':
                    $role_id=1;
                    break;
                case 'Assistant Principal':
                    $role_id = 2;
                    break;
                case 'Teacher':
                    $role_id = 3;
                    break;
                default:
                    $role_id = 3;
                    break;
            }

            $techers->assignRoles()->attach(array(
                $teacher_id => $role_id
            ));
        }
        return true;
    }

    protected static function updateClasses($data){
        $organization_id = self::$data['parms']['organization_id'];
        foreach ($data as $title) {
            if (isset($title['Offical Class #']) && $title['Offical Class #']!='') {
                $group = Group::firstOrCreate(['organization_id' => $organization_id, 'title' => $title['Offical Class #']]);
                $group->organization_id = $organization_id;
                $group->title = $title['Offical Class #'];
                $group->description = $title['Class Number'];
                $group->save();
            }
        }
        return true;
    }

}