<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\InputModel;

class InputController extends Controller{
    public function viewList(){
        return view('index');
    }

    public function getData(){
        try {
            $que = InputModel::all();
    
            $data = [];
            $no = 1;
    
            foreach ($que as $rowData) {
                $row = [];
                $row[] = $no++;
                $row[] = $rowData->input1;
                $row[] = $rowData->input2;
                $row[] = $rowData->matched_percentage;
                $row[] = '<button type="button" class="btn btn-warning showData" data-id="' . $rowData->id  . '"> Edit </button>';
                $row[] = '<button type="button" class="btn btn-danger deleteData" data-id="' . $rowData->id . '"> Delete </button>';
                $data[] = $row;
            }
    
            return response()->json([
                "draw" => 1,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data  // Return the data array correctly
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }
    

    public function insertData(Request $req){
        try {
            $input1 = $req->input('input1');
            $input2 = $req->input('input2');

            $input1Lower = strtolower($input1);
            $input2Lower = strtolower($input2);

            // FORMULA
            $matchingChars = 0;
            foreach (str_split($input1Lower) as $char) {
                if (strpos($input2Lower, $char) !== false) {
                    $matchingChars++;
                }
            }

            $totalChars = strlen($input1);
            $matchedPercentage = 0;

            if ($totalChars > 0) {
                if ($matchingChars > 0) { 
                    $matchedPercentage = ($matchingChars / $totalChars) * 100;
                } else {
                    $matchedPercentage = 0;
                }
            }

            InputModel::create([
                'input1' => $input1,
                'input2' => $input2,
                'matched_percentage' => round($matchedPercentage, 2), 
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Insert Success!'
            ]);

        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getDetailData($id){
        try {
            $data = InputModel::find($id); 

            return response()->json([
                'data' => $data
            ]);
        
        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function saveChangesData(Request $req, $id){
        try {
            $input1 = $req->input('input1_detail');
            $input2 = $req->input('input2_detail');

            $input1Lower = strtolower($input1);
            $input2Lower = strtolower($input2);

            // FORMULA
            $matchingChars = 0;
            foreach (str_split($input1Lower) as $char) {
                if (strpos($input2Lower, $char) !== false) {
                    $matchingChars++;
                }
            }

            $totalChars = strlen($input1);
            $matchedPercentage = 0;

            if ($totalChars > 0) {
                $matchedPercentage = ($matchingChars / $totalChars) * 100;
            }

            $data = InputModel::findOrFail($id);
            $data->input1 = $input1;
            $data->input2 = $input2;
            $data->matched_percentage = round($matchedPercentage, 2);
            $data->save();

            return response()->json([
                'code' => 200,
                'message' => 'Data updated successfully'
            ]);

        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function deleteData($id){
        try {
            $data = InputModel::findOrFail($id);
            $data->delete();
        
            return response()->json([
                'code' => 200,
                'message' => 'Data deleted successfully'
            ]);

        } catch (Exception $ex) {
            return response()->json([
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            ]);
        }
    }

}