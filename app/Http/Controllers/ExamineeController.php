<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use tidy;
use DOMDocument;
use DOMXPath;
use DB;

use App\Examinee;
use App\Division;
use App\School;

class ExamineeController extends Controller
{
    protected function download() {

        DB::delete('delete from examinees');
        DB::delete('delete from schools');
        DB::delete('delete from divisions');

        $data = file_get_contents(Storage::path('public/contents.html'));

        $tidy = new tidy;
        $config = array(
            'clean' => true,
            'output-xhtml' => true,
            'show-body-only' => true,
            'wrap' => 0,
            'show-warnings' => false
        );

        $tidy->parseString($data, $config, 'utf8');
        $tidy->cleanRepair();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($data);
        $xpathDoc = new DOMXpath($doc);

        // names width: 270px; text-align: left; padding: 0px 17px
        $names = $xpathDoc->query('//div[contains(@style,"width: 270px; text-align: left; padding: 0px 17px")]');

        // schools width: 300px; padding: 0px 11px;
        $schools = $xpathDoc->query('//div[contains(@style,"width: 300px; padding: 0px 11px")]');
        $schoolsArr = [];

        // divisions width: 300px; padding: 0px 11px;
        $divisions = $xpathDoc->query('//div[contains(@style,"width: 179px; padding: 0px 17px 0px 16px;")]');
        $divisionsArr = [];


        foreach ($divisions as $key => $val) {

            $divisionsArr[] = $val->nodeValue;

            $division = $val->nodeValue;

            $existDivision = Division::whereName($division)->first();

            // create division
            if (!$existDivision) {

                $newDiv = Division::create(['name' => $division]);
                echo "Create Division {$newDiv->name}<br>";
            }
        }

        foreach ($schools as $key => $val) {

            $schoolsArr[] = $val->nodeValue;

            $school = $val->nodeValue;

            $existSchool = School::whereName($school)->first();

            // create school

            if (!$existSchool) {

                // get division

                $division = Division::whereName($divisionsArr[$key])->first();

                if ($division) {

                    $newSchool = School::create(['name' => $school, 'division_id' => $division->id]);

                    echo "Create School {$newSchool->name} is from {$division->name} <br>";

                }
            }
        }

        foreach ($names as $key => $val) {

            $name = explode(",", $val->nodeValue);
            $firstName = $name[1];
            $lastName = $name[0];

            $school = $schoolsArr[$key];

            // check examinee

            $examinee = Examinee::where('first_name', $firstName)->where('last_name', $lastName)->first();
            $school = School::whereName($school)->first();

            if (!$examinee && $school) {

                $newEx = Examinee::create(['first_name' => $firstName, 'last_name' => $lastName, 'school_id' => $school->id]);

                echo ($key + 1 . " -- " . $newEx->first_name . ", " . $newEx->last_name . " -- school " . $school->name . "<br>");
            }


        }

        echo "<script>alert('Done Uploading'); location.href=\"/\";</script>";

    }

    protected function index(Request $request)
    {
        $examinees = Examinee::query();

        if ($request->filter != '') {

            $keywords = $request->filter;
            $examinees = Examinee::where('first_name', 'like', '%' . $keywords . '%')
                        ->orWhere('last_name', 'like', '%' . $keywords . '%')
                        ->orWhere(function ($query) use ($keywords) {
                            $query->orWhereHas('School', function ($query) use ($keywords) {
                                $query->where('name', 'like', '%' . $keywords . '%');
                            });

                            $query->orWhereHas('School.Division', function ($query) use ($keywords) {
                                $query->where('name', 'like', '%' . $keywords . '%');
                            });
                        });
        }



        $examinees = $examinees->with('School')->with('School.Division')->orderBy('last_name','asc')->paginate(50);

        return response()->json($examinees);
    }

    protected function store(Request $request)
    {

        $this->validate($request, [
            'first_name'        => 'required',
            'last_name'         => 'required'
        ]);


        try {

            // check school
            if ($request->school) {

                $school = School::whereName($request->school);

                if ($school) {

                    $newSchool = School::create(['name' => $request->school]);

                }
            }

            $newExaminee = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name
            ];

            if (isset($newSchool)) {

                $newExaminee['school_id'] = $newSchool->id;
            }

            Examinee::create($newExaminee);

            return response()->json(['status'=>'success','message'=>'Examinee successfully saved !']);
        }
        catch(\Exception $e){

            return response()->json(['status'=>'error','message'=>'Something Error Found!, Please try again']);
        }
    }

    protected function schools(Request $request) {

        $schools = School::withCount('passers')->orderBy('passers_count', 'desc')->get();

        return view('schools')->with('schools', $schools);
    }
}
