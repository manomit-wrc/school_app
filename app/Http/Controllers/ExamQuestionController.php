<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use Validator;
use App\ExamQuestionAnswer;
use Image;
use DB;

class ExamQuestionController extends Controller
{
    public function index() {
    	$fetch_all_question = ExamQuestionAnswer::where('status', '1')->orderby('id',  'desc')->get()->toArray();
    	foreach ($fetch_all_question as $key => $value) {
            $fetch_exam_details = Exam::find($value['exam_id'])->toArray();
            $fetch_all_question[$key]['exam'] = $fetch_exam_details['name'];
        }
        $fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
    	return view('frontend.exam_question.listings')->with('fetch_all_question', $fetch_all_question)->with('fetch_all_exam', $fetch_all_exam);
    }

    public function add_qustion_view() {
    	$fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
    	return view ('frontend.exam_question.add')->with('fetch_all_exam', $fetch_all_exam);
    }

    public function add_qustion_submit(Request $request) {
    	Validator::make($request->all(),[
    		'exam' => 'required',
    		'question_type' => 'required|in:text,image',
    		'option_type'=> 'required|in:mcq,numeric',
    		'explanation_details' => 'required',
    		'explanation_file' => 'mimetypes:image/jpeg,image/png,image/jpg|max:6144'
    	],[
    		'exam.required' => 'Please select exam',
    		'question_type.required' => 'Please select question type',
    		'option_type.required' => 'Please select option type',
    		'explanation_details.required' => 'Please enter explanation details',
    		'explanation_file.*.mimetypes' => 'Please upload correct file',
    		'explanation_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
        	$fileName1 = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/question_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(320, 320, function ($constraint) {
				$constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName1);
            //original destination path
            $destinationPath = public_path().'/upload/question_file/original/';
            $file->move($destinationPath, $fileName1);
        }

    	if (isset($request->option_type_A) && $request->option_type_A == 'text') {
			$optionA_type = $request->option_type_A;
			$optionA = $request->optionA;
		} else {
			$optionA_type = '';
			$optionA = '';
		}

		if (isset($request->option_type_A) && $request->option_type_A == 'image') {
			$optionA_type = $request->option_type_A;
			if ($request->hasFile('optionA_file')) {
	            $file = $request->file('optionA_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        }
			$optionA = $fileName;
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'text') {
			$optionB_type = $request->option_type_B;
			$optionB = $request->optionB;
		} else {
			$optionB_type = '';
			$optionB = '';
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'image') {
			$optionB_type = $request->option_type_B;
			if ($request->hasFile('optionB_file')) {
	            $file = $request->file('optionB_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        }
			$optionB = $fileName;
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'text') {
			$optionC_type = $request->option_type_C;
			$optionC = $request->optionC;
		} else {
			$optionC_type = '';
			$optionC = '';
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'image') {
			$optionC_type = $request->option_type_C;
			if ($request->hasFile('optionC_file')) {
	            $file = $request->file('optionC_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        }
			$optionC = $fileName;
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'text') {
			$optionD_type = $request->option_type_D;
			$optionD = $request->optionD;
		} else {
			$optionD_type = '';
			$optionD = '';
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'image') {
			$optionD_type = $request->option_type_D;
			if ($request->hasFile('optionD_file')) {
	            $file = $request->file('optionD_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        }
			$optionD = $fileName;
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'text') {
			$optionE_type = $request->option_type_E;
			$optionE = $request->optionE;
		} else {
			$optionE_type = '';
			$optionE = '';
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'image') {
			$optionE_type = $request->option_type_E;

			if ($request->hasFile('optionE_file')) {
	            $file = $request->file('optionE_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        }
			$optionE = $fileName;
		}

    	$tempArray = array(
    		'optionA_type' => $optionA_type,
    		'optionA' => $optionA,
    		'optionB_type' => $optionB_type,
    		'optionB' => $optionB,
    		'optionC_type' => $optionC_type,
    		'optionC' => $optionC,
    		'optionD_type' => $optionD_type,
    		'optionD' => $optionD,
    		'optionE_type' => $optionE_type,
    		'optionE' => $optionE
    	);

    	if ($request->hasFile('explanation_file')) {
            $file = $request->file('explanation_file');
        	$explanation_file_name = time().'_'.$file->getClientOriginalName();
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/explanation_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(320, 320, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$explanation_file_name);
            //original destination path
            $destinationPath = public_path().'/upload/explanation_file/original/';
            $file->move($destinationPath, $explanation_file_name);
        } else {
            $explanation_file_name = "";
        }

    	$add = new ExamQuestionAnswer();
    	$add->exam_id = $request->exam;
    	
    	if ($request->question_type == 'text') {
    		$add->question_type = $request->question_type;
    		$add->question = $request->question;
    	}
    	if ($request->question_type == 'image') {
    		$add->question_type = $request->question_type;
    		$add->question = $fileName1;
    	}
    	$add->option_type = $request->option_type;
    	$add->answer = serialize($tempArray);
    	$add->correct_answer = serialize($request->answer);
    	$add->numeric_answer = $request->numeric_correct_ans;
    	$add->status = 1;
    	$add->explanation_details = $request->explanation_details;
    	$add->explanation_file = $explanation_file_name;

    	if ($add->save()) {
    		$request->session()->flash("submit-status", "Exam Question added successfully.");
            return redirect('/exam_question');
    	} else {
            $request->session()->flash("submit-status", "Exam Question addition failed.");
            return redirect('/exam_question/add');
        }
    }

    public function edit(Request $request, $question_id) {
    	$fetch_question_details = ExamQuestionAnswer::find($question_id)->toArray();
    	if ($fetch_question_details['option_type'] != 'numeric') {
    		$option = unserialize($fetch_question_details['answer']);
    		$correct_answer = unserialize($fetch_question_details['correct_answer']);
    	} else {
    		$option = '';
    		$correct_answer = '';
    	}
    	$fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
    	return view('frontend.exam_question.edit')->with('fetch_question_details', $fetch_question_details)->with('option', $option)->with('correct_answer', $correct_answer)->with('fetch_all_exam', $fetch_all_exam);
    }

    public function edit_submit(Request $request, $question_id) {
    	Validator::make($request->all(),[
    		'exam' => 'required',
    		'question_type' => 'required|in:text,image',
    		'option_type'=> 'required|in:mcq,numeric',
    		'explanation_details' => 'required' ,
    		'explanation_file' => 'mimetypes:image/jpeg,image/png,image/jpg|max:6144' 
    	],[
    		'exam.required' => 'Please select exam type',
    		'question_type.required' => 'Please select question type',
    		'option_type.required' => 'Please select option type',
            'explanation_details.required' => 'Please enter explanation details',
    		'explanation_file.*.mimetypes' => 'Please upload correct file',
    		'explanation_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
        	$fileName1 = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/question_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(320, 320, function ($constraint) {
            	$constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName1);
            //original destination path
            $destinationPath = public_path().'/upload/question_file/original/';
            $file->move($destinationPath, $fileName1);
        } else {
        	$fileName1 = $request->exit_question_image;
        }

    	if (isset($request->option_type_A) && $request->option_type_A == 'text') {
			$optionA_type = $request->option_type_A;
			$optionA = $request->optionA;
		} else {
			$optionA_type = '';
			$optionA = '';
		}

		if (isset($request->option_type_A) && $request->option_type_A == 'image') {
			$optionA_type = $request->option_type_A;
			if ($request->hasFile('optionA_file')) {
	            $file = $request->file('optionA_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        } else {
	        	$fileName = $request->exit_optionA_image;
	        }
			$optionA = $fileName;
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'text') {
			$optionB_type = $request->option_type_B;
			$optionB = $request->optionB;
		} else {
			$optionB_type = '';
			$optionB = '';
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'image') {
			$optionB_type = $request->option_type_B;
			if ($request->hasFile('optionB_file')) {
	            $file = $request->file('optionB_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        } else {
	        	$fileName = $request->exit_optionB_image;
	        }
			$optionB = $fileName;
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'text') {
			$optionC_type = $request->option_type_C;
			$optionC = $request->optionC;
		} else {
			$optionC_type = '';
			$optionC = '';
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'image') {
			$optionC_type = $request->option_type_C;

			if ($request->hasFile('optionC_file')) {
	            $file = $request->file('optionC_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        } else {
	        	$fileName = $request->exit_optionC_image;
	        }
			$optionC = $fileName;
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'text') {
			$optionD_type = $request->option_type_D;
			$optionD = $request->optionD;
		} else {
			$optionD_type = '';
			$optionD = '';
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'image') {
			$optionD_type = $request->option_type_D;
			if ($request->hasFile('optionD_file')) {
	            $file = $request->file('optionD_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        } else {
	        	$fileName = $request->exit_optionD_image;
	        }
			$optionD = $fileName;
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'text') {
			$optionE_type = $request->option_type_E;
			$optionE = $request->optionE;
		} else {
			$optionE_type = '';
			$optionE = '';
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'image') {
			$optionE_type = $request->option_type_E;
			if ($request->hasFile('optionE_file')) {
	            $file = $request->file('optionE_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(320, 320, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath, $fileName);
	        } else {
	        	$fileName = $request->exit_optionE_image;
	        }
			$optionE = $fileName;
		}

    	$tempArray = array(
    		'optionA_type' => $optionA_type,
    		'optionA' => $optionA,
    		'optionB_type' => $optionB_type,
    		'optionB' => $optionB,
    		'optionC_type' => $optionC_type,
    		'optionC' => $optionC,
    		'optionD_type' => $optionD_type,
    		'optionD' => $optionD,
    		'optionE_type' => $optionE_type,
    		'optionE' => $optionE
    	);

    	if ($request->hasFile('explanation_file')) {
            $file = $request->file('explanation_file');
        	$explanation_file_name = time().'_'.$file->getClientOriginalName();
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/explanation_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(320, 320, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$explanation_file_name);
            //original destination path
            $destinationPath = public_path().'/upload/explanation_file/original/';
            $file->move($destinationPath, $explanation_file_name);
        } else {
        	$explanation_file_name = $request->exit_explanation_image;
        }

    	$edit = ExamQuestionAnswer::find($question_id);
    	$edit->exam_id = $request->exam;
    	$edit->explanation_details = $request->explanation_details;
    	$edit->explanation_file = $explanation_file_name;
    	
    	if ($request->question_type == 'text') {
    		$edit->question_type = $request->question_type;
    		$edit->question = $request->question;
    	}
    	if ($request->question_type == 'image') {
    		$edit->question_type = $request->question_type;
    		$edit->question = $fileName1;
    	}
    	$edit->option_type = $request->option_type;
    	if ($request->option_type == 'mcq') {
    		$edit->answer = serialize($tempArray);
    		$edit->correct_answer = serialize($request->answer);
    		$edit->numeric_answer = '';
    	}
    	if ($request->option_type == 'numeric') {
    		$edit->answer = '';
	    	$edit->correct_answer = '';
	    	$edit->numeric_answer = $request->numeric_correct_ans;
    	}
    	
    	if ($edit->save()) {
    		$request->session()->flash("submit-status", "Question updated successfully.");
            return redirect('/exam_question');
    	} else {
            $request->session()->flash("submit-status", "Question updation failed.");
            return redirect('/exam_question/edit/'.$question_id);
        }
    }

    public function delete(Request $request, $question_id) {
        $fetch_question = ExamQuestionAnswer::find($question_id);
        $fetch_question->status = 5;
        if ($fetch_question->save()) {
            $request->session()->flash("submit-status", "Question deleted successfully.");
            return redirect('/exam_question');
        } else {
            $request->session()->flash("submit-status", "Question deletion failed.");
            return redirect('/exam_question');
        }
    }

    public function filter_submit(Request $request) {
    	$output = array();
    	$question = DB::table('exam_question_answers')
    				->where('status', '=', '1')
    				->when($request->exam, function($query) use ($request) {
    					return $query->where('exam_id', $request->exam);
    				})
    				->get()->toArray();
    	foreach ($question as $key => $value) {
    		$new_question = (array) $value;
            $fetch_exam_details = Exam::find($value['exam_id'])->toArray();
            $new_question['exam'] = $fetch_exam_details[0]['name'];
            array_push($output, $new_question);
        }
        $fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
        return view ('frontend.exam_question.listings')->with('fetch_all_question', $output)->with('fetch_all_exam', $fetch_all_exam);
    }
}
