<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index(Request $request){
        $student = Student::all();
        $query = Student::query();

        
        if ($student->isEmpty()) {
            $data = [ 
                'message' => 'No se han encontrado estudiantes',
                'status' => 200
            ];
            return response()->json($data,200);
        }

        if ($request->has('name')) {
            $query->where('name','like', '%' . $request->name . '%');
        }

        if ($request->has('programming_language')) {
            $query->where('programming_language', $request->input('programming_language'));
        }

        $data = [
            'students' => $student,
            'status' => 200
        ];

        return response()->json($data,200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:student',
            'phone' => 'digits:10',
            'programming_language' => 'in:Python,Ruby,PHP,Java,Go,Kotlin,C#,C,C++,Rust,JavaScript'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => '400'
            ];
            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'programming_language' => $request->input('programming_language')
        ]);

        $data = [
            'message' => 'Estudiante creado exitosamente',
            'student' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id){
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'student'=> $student,
            'status'=> 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id){
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:student',
            'phone' => 'digits:10',
            'programming_language' => 'in:Python,Ruby,PHP,Java,Go,Kotlin,C#,C,C++,Rust,JavaScript'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'error' => $validator->errors(),
                'status' => '400'
            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->programming_language = $request->input('programming_language');

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado exitosamente',
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }


    public function destroy($id){
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Estudiante eliminado exitosamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

}
