<?php

namespace App\Http\Controllers;

use Input;
use Log;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\News;

class NewsServiceController extends Controller
{
    public function searchStatus($status)
    {
		if($oNews = News::where('state', $status)->paginate()) {
            $aRet = [
                'success' => 'true',
                'news' => $oNews->toArray()
            ];
        } else {
            $aRet = [
                'success' => 'false',
                'error' => 'no data available'
            ];
        }
        return response()->json($aRet);
    }
    
    public function search()
    {
		return $this->searchStatus('visible');
	}
    
    public function show($id)
    {
    	if($oNews = News::where('id', $id)->with(['comments'])->get()) {
            $aRet = [
                'success' => 'true',
                'news' => $oNews->toArray()
            ];
        } else {
            $aRet = [
                'success' => 'false',
                'error' => 'cant find news with this id'
            ];
        }
        return response()->json($aRet);
    }

	public function update($id) {

		$aNewsData = Input::get('news');
		$validator = Validator::make($aNewsData, [
			'title' => 'sometimes|required|max:255',
			'content' => 'sometimes|required',
			'state' => 'sometimes|in:created,visible,deleted'
		]);
		if($validator->fails()) {
			$aRet = [
	            'success' => 'false',
	            'errors'        => $validator->errors()
	        ];
		} elseif($oNews = News::find($id) AND $oNews->fill($aNewsData)->update()) {
           	$aRet = [
	            'success' => 'true'
	        ];
		} else {
		    $aRet = [
		        'success' => 'false'
		    ];
		}
        return response()->json($aRet);
	}

	public function create() {

		$aNewsData = Input::get('news');
		$validator = Validator::make($aNewsData, [
			'title' => 'required|max:255',
			'content' => 'required',
			'state' => 'in:created,visible,deleted'
		]);
		if($validator->fails()) {
			$aRet = [
	            'success' => 'false',
	            'errors'        => $validator->errors()
	        ];
		} elseif($oNews = News::create($aNewsData)) {
           	$aRet = [
	            'success' => 'true'
	        ];
		} else {
		    $aRet = [
		        'success' => 'false'
		    ];
		}
        return response()->json($aRet);

	}
}
